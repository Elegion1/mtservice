<?php

namespace App\Traits;

trait HasSeoMeta
{
    /**
     * Prepare page data with SEO metadata
     * 
     * @param string $view View name to render
     * @param string $link Link key for SEO map and page data
     * @param array $extraData Additional data to pass to view
     * @return \Illuminate\View\View
     */
    public function viewWithSeo($view, $link, $extraData = [])
    {
        $data = $this->getPageData($link, $extraData);
        $seo = $this->seoMap();
        
        $data['seoTitle'] = $seo[$link]['title'] ?? null;
        $data['seoDescription'] = $seo[$link]['description'] ?? null;
        
        return view($view, $data);
    }
}
