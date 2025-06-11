<?php

namespace App\Http\Controllers;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use JoeDixon\Translation\Drivers\Translation;

class LanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private Translation $translation;
    private string $languageFilesPath;

    public function __construct(Translation $translation)
    {
        $this->translation = $translation;
        $this->languageFilesPath = resource_path('lang');
    }

    public function index()
    {
        $search = request()->get('search');
        $perPage = settings('per_page', 10);
        $languages = Language::search($search)->paginate($perPage);
        return view('language.index', compact('languages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('language.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'locale' => ['required', 'unique:languages,locale'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $input = $request->all();
        $data = [
            'name' => $input['name'],
            'locale' => $input['locale'],
            'is_default' => $input['is_default'],
            'status' => $input['status'],
        ];
        if ($input['is_default']) {
            DB::table('languages')->update(['is_default' => 0]);
            $data['status'] = 1;
        }
        $this->translation->addLanguage($input['locale'], $input['name']);
        Language::create($data);

        return redirect()->route('language.index')->with('success', __('Language has been stored successfully!'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Language $language)
    {
        return view('language.edit', compact('language'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Language $language)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'locale' => ['required', 'unique:languages,locale,'.$language->id],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        $input = $request->all();
        $data = [
            'name' => $input['name'],
            'locale' => $input['locale'],
            'is_default' => (bool) $input['is_default'],
            'status' => (bool) $input['status'],
        ];

        if ($language->is_default && ! $input['is_default']) {
            return redirect()->back()->with('error', __('Please set default language'));
        }

        if ($input['is_default']) {
            DB::table('languages')->update(['is_default' => 0]);
            $data['status'] = 1;
        }

        /* Existing File name */
        $filePath = (string)($this->languageFilesPath) .DIRECTORY_SEPARATOR."{$language->locale}.json";
        /* New File name */
        $newFileName = (string)($this->languageFilesPath) .DIRECTORY_SEPARATOR."{$input['locale']}.json";
        rename($filePath, $newFileName);

        /* Existing File name */
        $folderPath = (string)($this->languageFilesPath) .DIRECTORY_SEPARATOR."{$language->locale}";
        /* New File name */
        $newFolderName = (string)($this->languageFilesPath) .DIRECTORY_SEPARATOR."{$input['locale']}";
        rename($folderPath, $newFolderName);

        $language->update($data);

        return redirect()->route('language.index')->with('success', __('Language has been updated successfully!'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Language $language)
    {
        \File::delete((string)($this->languageFilesPath) .DIRECTORY_SEPARATOR."{$language->locale}.json");
        \File::deleteDirectory((string)($this->languageFilesPath) .DIRECTORY_SEPARATOR. (string)$language->locale);
        $language->delete();

        return redirect()->route('language.index')->with('success', __('Language has been deleted successfully!'));
    }

    public function languageKeyword(Request $request, $language)
    {

        if ($request->has('language') && $request->get('language') !== $language) {
            return redirect()
                ->route('language-keyword', ['language' => $request->get('language'), 'group' => $request->get('group'), 'filter' => $request->get('filter')]);
        }

        $languages = $this->translation->allLanguages();
        $groups = $this->translation->getGroupsFor(config('app.key_locale'))->merge('single');
        $translations = $this->translation->filterTranslationsFor($language, $request->get('filter'))->forPage($request->get('page'), settings('per_page', 10));

        if ($request->has('group') && $request->get('group')) {
            if ($request->get('group') === 'single') {
                $translations = $translations->get('single');
                $translations = new Collection(['single' => $translations]);
            } else {
                $translations = $translations->get('group')->filter(function ($values, $group) use ($request) {
                    return $group === $request->get('group');
                });

                $translations = new Collection(['group' => $translations]);
            }
        }
        return view('language.keyword', compact('language', 'languages', 'groups', 'translations'));
    }

    public function keywordUpdate(Request $request)
    {
        $group = $request->group;
        $language = $request->language;
        $isGroupTranslation = ! Str::contains($group, 'single');

        $this->translation->add($request, $language, $isGroupTranslation);

        return redirect()->back()->with('success', __('Keyword has been updated successfully!'));
    }

    public function syncMissing()
    {
        Artisan::call('translation:sync-missing-translation-keys');

        return redirect()->back()->with('success', __('Successfully Sync Missing Translation Keys'));
    }
}
