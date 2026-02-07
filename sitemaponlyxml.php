<?php
// Sitemap extension, https://github.com/annaesvensson/yellow-sitemap

class YellowSitemapOnlyXml
{
    const VERSION = "0.9.1";
    public $yellow;         // access to API

    // Handle initialisation
    public function onLoad($yellow)
    {
        $this->yellow = $yellow;
    }

    // Handle page extra data
    public function onParsePageLayout($page, $name)
    {
        if ($this->isRequestXml($page)) {
            $pages = $this->yellow->content->index();
            $this->yellow->page->setHeader("Content-Type", "text/xml; charset=utf-8");
            $output = "<?xml version=\"1.0\" encoding=\"utf-8\"\077>\r\n";
            $output .= "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
            foreach ($pages as $pageSitemap) {
                $output .= "<url><loc>" . $pageSitemap->getUrl() . "</loc></url>\r\n";
            }
            $output .= "</urlset>\r\n";
            $this->yellow->page->setOutput($output);
        }
    }

    public function onParsePageExtra($page, $name)
    {
        $output = null;
        if ($name == "header") {
            $locationSitemap = $this->yellow->system->get("coreServerBase") . "/";
            $locationSitemap .= $this->yellow->lookup->normaliseArguments("page:sitemap.xml");
            $output = "<link rel=\"sitemap\" type=\"text/xml\" href=\"$locationSitemap\" />\n";
        }
        return $output;
    }

    // Check if XML requested
    public function isRequestXml($page)
    {
        return $page->getRequest("page") == "sitemap.xml";
    }
}
