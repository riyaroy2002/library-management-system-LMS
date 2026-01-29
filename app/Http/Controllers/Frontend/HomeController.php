<?php

namespace App\Http\Controllers\Frontend;

use App\Models\{Cms,Gallery,District};

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $hero      = Cms::where('slug', 'hero-section')->where('status', 'published')->first();
        $title     = $hero?->text_content ?? '';
        $highlight = null;
        if (stripos($title, 'Knowledge') !== false) {
            $highlight = 'Knowledge';
            $titleWithoutHighlight = str_ireplace($highlight, '', $title);
        } else {
            $titleWithoutHighlight = $title;
        }

        $mission          = Cms::where('slug', 'our-mission')->where('status', 'published')->first();
        $missionItems     = json_decode($mission?->extra)->items ?? [];
        $vision           = Cms::where('slug', 'our-vision')->where('status', 'published')->first();
        $visionItems      = json_decode($vision?->extra, true)['points'] ?? [];

        return view('frontend.index', compact(
            'hero',
            'titleWithoutHighlight',
            'highlight',
            'mission',
            'missionItems',
            'vision',
            'visionItems'
        ));
    }

    public function aboutUs()
    {
        $about = Cms::where('slug', 'about-us')->where('status', 'published')->first();
        $extra = $about ? json_decode($about->extra, true) : [];
        return view('frontend.about-us', compact('about','extra'));
    }

    public function contactUs()
    {
        $contact_us = Cms::where('slug', 'contact-us')->where('status', 'published')->first();
        return view("frontend.contact-us",compact('contact_us'));
    }

    public function gallery()
    {
        $gallery = Gallery::latest()->get();
        return view('frontend.gallery', compact('gallery'));
    }

    public function getDistrict($state_id)
    {
        return District::where('state_id', $state_id)->get();
    }

    public function exploreNow()
    {
        return view('frontend.explore-now');
    }
}
