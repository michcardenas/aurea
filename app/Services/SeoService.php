<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\Product;

class SeoService
{
    /**
     * Generate meta tags array for a page.
     *
     * @return array{title: string, description: string, og_title: string, og_description: string, og_image: string, canonical: string}
     */
    public function meta(string $title, string $description, ?string $image = null, ?string $canonical = null, string $ogType = 'website'): array
    {
        return [
            'title' => $title,
            'description' => $description,
            'canonical' => $canonical ?? url()->current(),
            'og_type' => $ogType,
            'og_title' => $title,
            'og_description' => $description,
            'og_image' => $image ?? asset('images/og-default.jpg'),
            'twitter_card' => 'summary_large_image',
            'twitter_title' => $title,
            'twitter_description' => $description,
            'twitter_image' => $image ?? asset('images/og-default.jpg'),
        ];
    }

    /**
     * Generate meta tags for a product page.
     */
    public function forProduct(Product $product): array
    {
        $title = $product->meta_title ?: "{$product->name} | nuvion - glass";
        $description = $product->meta_description ?: mb_substr(strip_tags($product->description), 0, 160);
        $image = is_array($product->images) ? ($product->images[0] ?? null) : null;

        return $this->meta(
            $title,
            $description,
            $image ? asset("storage/{$image}") : null,
            route('products.show', $product->slug),
            'product',
        );
    }

    /**
     * Generate meta tags for a blog post.
     */
    public function forBlogPost(BlogPost $post): array
    {
        $title = $post->meta_title ?: "{$post->title} | nuvion - glass";
        $description = $post->meta_description ?: mb_substr(strip_tags($post->excerpt ?? $post->content), 0, 160);
        $image = $post->image ? asset("storage/{$post->image}") : null;
        $canonical = $post->canonical_url ?: route('blog.show', $post->slug);

        $meta = $this->meta($title, $description, $image, $canonical, 'article');

        // Override with OG-specific fields if set
        if ($post->og_title) {
            $meta['og_title'] = $post->og_title;
            $meta['twitter_title'] = $post->og_title;
        }
        if ($post->og_description) {
            $meta['og_description'] = $post->og_description;
            $meta['twitter_description'] = $post->og_description;
        }
        if ($post->og_image) {
            $ogImage = asset("storage/{$post->og_image}");
            $meta['og_image'] = $ogImage;
            $meta['twitter_image'] = $ogImage;
        }

        return $meta;
    }

    /**
     * Generate Organization schema for the home page.
     */
    public function organizationSchema(): string
    {
        return $this->toJsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'nuvion - glass',
            'url' => url('/'),
            'logo' => asset('images/logo.png'),
            'description' => 'Lentes con protección de luz azul. Protege tus ojos de las pantallas con estilo.',
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'availableLanguage' => 'Spanish',
            ],
        ]);
    }

    /**
     * Generate Product schema for a product page.
     */
    public function productSchema(Product $product): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => mb_substr(strip_tags($product->description), 0, 300),
            'url' => route('products.show', $product->slug),
            'brand' => [
                '@type' => 'Brand',
                'name' => 'nuvion - glass',
            ],
            'offers' => [
                '@type' => 'Offer',
                'url' => route('products.show', $product->slug),
                'priceCurrency' => 'MXN',
                'price' => number_format($product->price, 2, '.', ''),
                'availability' => $product->stock > 0
                    ? 'https://schema.org/InStock'
                    : 'https://schema.org/OutOfStock',
            ],
        ];

        if ($product->images && count($product->images) > 0) {
            $schema['image'] = array_map(fn ($img) => asset("storage/{$img}"), $product->images);
        }

        return $this->toJsonLd($schema);
    }

    /**
     * Generate Article schema for a blog post.
     */
    public function articleSchema(BlogPost $post): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => $post->schema_type ?? 'BlogPosting',
            'headline' => $post->title,
            'description' => $post->meta_description ?? $post->excerpt ?? mb_substr(strip_tags($post->content), 0, 160),
            'url' => route('blog.show', $post->slug),
            'datePublished' => $post->published_at?->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => $post->author_name ?? 'nuvion glass',
                'url' => url('/'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => 'nuvion glass',
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('img/isotipo.png'),
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('blog.show', $post->slug),
            ],
        ];

        if ($post->image) {
            $schema['image'] = asset("storage/{$post->image}");
        }

        if ($post->focus_keyword) {
            $schema['keywords'] = $post->focus_keyword;
        }

        return $this->toJsonLd($schema);
    }

    /**
     * Generate FAQPage schema from an array of Q&A pairs.
     *
     * @param  array<int, array{question: string, answer: string}>  $faqs
     */
    public function faqSchema(array $faqs): string
    {
        return $this->toJsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn ($faq) => [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer'],
                ],
            ], $faqs),
        ]);
    }

    /**
     * Generate BreadcrumbList schema.
     *
     * @param  array<int, array{name: string, url: string}>  $items
     */
    public function breadcrumbSchema(array $items): string
    {
        return $this->toJsonLd([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(fn ($item, $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'],
            ], $items, array_keys($items)),
        ]);
    }

    /**
     * Encode schema data as a JSON-LD script tag.
     */
    private function toJsonLd(array $data): string
    {
        $json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        return "<script type=\"application/ld+json\">\n{$json}\n</script>";
    }
}
