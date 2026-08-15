<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Http\Request;

class UserDetailController extends Controller
{
    public function index()
    {
        $userDetails = UserDetail::latest()->get();

        return view('user-details-list', compact('userDetails'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:30',
            'company' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'requirements' => 'nullable|string',

            // Company Analysis fields
            'website_title' => 'nullable|string',
            'website_description' => 'nullable|string',
            'website_headings' => 'nullable|string',
        ]);

        UserDetail::create($validated);

        return redirect('/user-details')
            ->with('success', 'User details saved successfully!');
    }

    public function scrapeWebsite(Request $request)
    {
        $request->validate([
            'website' => 'required|url|max:255',
        ]);

        $website = trim($request->input('website'));

        try {

            // Fetch website directly using PHP
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 20,
                    'ignore_errors' => true,
                    'header' =>
                        "User-Agent: Mozilla/5.0\r\n" .
                        "Accept: text/html,application/xhtml+xml\r\n",
                ],

                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);

            $html = file_get_contents(
                $website,
                false,
                $context
            );

            if ($html === false || trim($html) === '') {

                return response()->json([
                    'success' => false,
                    'error' => 'Could not download website content.',
                    'output' => '',
                ]);
            }

            // ==========================================
            // EXTRACT TITLE
            // ==========================================

            $title = 'Not found';

            if (preg_match(
                '/<title[^>]*>(.*?)<\/title>/is',
                $html,
                $matches
            )) {

                $title = trim(
                    html_entity_decode(
                        strip_tags($matches[1])
                    )
                );
            }

            // ==========================================
            // EXTRACT META DESCRIPTION
            // ==========================================

            $description = 'Not found';

            if (preg_match(
                '/<meta[^>]+name=["\']description["\'][^>]+content=["\'](.*?)["\']/is',
                $html,
                $matches
            )) {

                $description = trim(
                    html_entity_decode(
                        $matches[1]
                    )
                );
            }

            // ==========================================
            // EXTRACT HEADINGS
            // ==========================================

            $headings = [];

            if (preg_match_all(
                '/<h[1-3][^>]*>(.*?)<\/h[1-3]>/is',
                $html,
                $matches
            )) {

                foreach ($matches[1] as $heading) {

                    $heading = trim(
                        html_entity_decode(
                            strip_tags($heading)
                        )
                    );

                    if ($heading !== '') {

                        $headings[] = $heading;
                    }
                }
            }

            // Remove duplicate headings
            $headings = array_values(
                array_unique($headings)
            );

            // Convert headings to database text
            $headingsText = implode(
                "\n",
                $headings
            );

            // ==========================================
            // SAVE ANALYSIS IF USER ALREADY EXISTS
            // ==========================================

            $userDetail = UserDetail::where(
                'website',
                $website
            )
            ->latest()
            ->first();

            if ($userDetail) {

                $userDetail->update([
                    'website_title' => $title,
                    'website_description' => $description,
                    'website_headings' => $headingsText,
                ]);
            }

            // ==========================================
            // BUILD RESPONSE
            // ==========================================

            $output = "Original URL: {$website}\n";
            $output .= "HTTP Status: 200\n\n";

            $output .= "Company Information\n";
            $output .= "--------------------\n";

            $output .= "Title: {$title}\n";
            $output .= "Description: {$description}\n\n";

            $output .= "Headings:\n";

            if (count($headings) > 0) {

                foreach ($headings as $heading) {

                    $output .= "- {$heading}\n";
                }

            } else {

                $output .= "- No headings found\n";
            }

            return response()->json([
                'success' => true,
                'exit_code' => 0,
                'output' => $output,
                'error' => '',
                'website' => $website,
                'saved_to_database' => $userDetail !== null,
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'success' => false,
                'exit_code' => 1,
                'output' => '',
                'error' => $e->getMessage(),
                'website' => $website,
                'saved_to_database' => false,
            ]);
        }
    }

    public function destroy($id)
    {
        $userDetail = UserDetail::findOrFail($id);

        $userDetail->delete();

        return redirect('/user-details-list')
            ->with('success', 'User details deleted successfully!');
    }
}