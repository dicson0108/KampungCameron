<?php

class PageController {
    public function about(): void {
        render_view('pages/about', ['title' => 'About Us — Kampung Cameron']);
    }

    public function contact(): void {
        render_view('pages/contact', ['title' => 'Contact Us — Kampung Cameron']);
    }

    public function terms(): void {
        render_view('pages/terms', ['title' => 'Terms & Conditions — Kampung Cameron']);
    }

    public function privacy(): void {
        render_view('pages/privacy', ['title' => 'Privacy Policy — Kampung Cameron']);
    }

    public function shipping(): void {
        render_view('pages/shipping', ['title' => 'Shipping & Delivery — Kampung Cameron']);
    }

    public function returnPolicy(): void {
        render_view('pages/return_policy', ['title' => 'Return Policy — Kampung Cameron']);
    }
}
