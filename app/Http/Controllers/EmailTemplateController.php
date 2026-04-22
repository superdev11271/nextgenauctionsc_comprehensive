<?php
namespace App\Http\Controllers;

use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    // Display a listing of the email templates
    public function index()
    {
        $emailTemplates = EmailTemplate::all();

        return view('backend.website_settings.mail_tamplate.index', compact('emailTemplates'));
    }


    // Show the form for editing an email template
    public function edit(EmailTemplate $emailTemplate)
    {
        return view('backend.website_settings.mail_tamplate.edit', compact('emailTemplate'));
    }

    // Update the specified email template in the database
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'redirect_url' => 'string',
        ]);

        $emailTemplate->update($request->all());

        return redirect()->route('email-templates.index')->with('success', 'Email template updated successfully.');
    }
    public function prepareTamplate($user, $product, $template)
    {
        if($user) $placeholders['%user_name%'] = $user->name;
        if($product) $placeholders['%product_name%'] = $product->name;
        if($product) $placeholders['%current_price%'] = $product->auction_product==0 ? $product->unit_price:$product->bids->max('amount');

        $title = isset($placeholders) ? $this->replacePlaceholders($template->subject, $placeholders):$template->subject;
        $body =  isset($placeholders) ? $this->replacePlaceholders($template->body, $placeholders):$template->body;
        return compact('title', 'body');
    }
    public function replacePlaceholders($template, $PlaceholderAndData)
    {
        return str_replace(array_keys($PlaceholderAndData), array_values($PlaceholderAndData), $template);
    }
}
