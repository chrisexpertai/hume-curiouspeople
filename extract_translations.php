<?php

// Function to extract translatable strings from Blade template
function extract_translations_from_blade($blade_file) {
    $content = file_get_contents($blade_file);
    preg_match_all('/{{\s*tr\([\'"](.*?)[\'"]\)\s*}}/', $content, $matches);
    return $matches[1];
}

// Directory containing your Laravel project
$project_dir = __DIR__;

// Array to store translations
$translations = [];

// Recursively search for Blade files in the project directory
$iterator = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($project_dir)
);
$regexIterator = new RegexIterator($iterator, '/^.+\.blade\.php$/i', RecursiveRegexIterator::GET_MATCH);

foreach ($regexIterator as $file) {
    $blade_file = $file[0];
    $keys = extract_translations_from_blade($blade_file);
    foreach ($keys as $key) {
        // Escape apostrophes
        $escaped_key = addslashes($key);
        // Set the translation to the same value as the key if empty
        $translations[$escaped_key] = $escaped_key;
    }
}

// Generate PHP file with translations
$output_file = 'translations.php';
$php_code = "<?php\n\nreturn [\n";
foreach ($translations as $key => $value) {
    $php_code .= "    '$key' => '$value',\n";
}
$php_code .= "];\n";

file_put_contents($output_file, $php_code);

echo "Translations generated successfully.\n";
