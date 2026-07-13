<?php

namespace App\Http\Controllers;

class ResultsController extends Controller
{
    public function vitCrnnResults()
    {
        $csvPath = base_path('Results/ViT-CRNN_Combined_Test_Set_Recognition_Results.csv');
        $lexiconPath = base_path('Results/Combined_Test_Set_Recognition_Results_lexicon.csv');
        
        $results = $this->parseCsv($csvPath);
        $lexicon = $this->parseCsv($lexiconPath);
        
        // Create a lexicon lookup array by No. for faster matching
        $lexiconLookup = [];
        foreach ($lexicon as $lex) {
            $key = trim($lex['No.']);
            $lexiconLookup[$key] = $lex;
        }
        
        // Merge lexicon corrections into results
        foreach ($results as &$result) {
            $resultNo = trim($result['No.']);
            if (isset($lexiconLookup[$resultNo])) {
                $result['predicted_label_lex'] = trim($lexiconLookup[$resultNo]['predicted_label_lex'] ?? '');
            } else {
                $result['predicted_label_lex'] = '';
            }
        }
        
        return view('vit-crnn-results', ['results' => $results]);
    }

    public function crnnResults()
    {
        $csvPath = base_path('Results/CRNN_Combined_Test_Set_Recognition_Results.csv');
        $results = $this->parseCsv($csvPath);
        
        return view('crnn-results', ['results' => $results]);
    }
    
    private function parseCsv($filePath)
    {
        if (!file_exists($filePath)) {
            return [];
        }
        
        $data = [];
        $handle = fopen($filePath, 'r');
        $header = fgetcsv($handle);
        
        while (($row = fgetcsv($handle)) !== false) {
            if (count($row) === count($header)) {
                $data[] = array_combine($header, $row);
            }
        }
        
        fclose($handle);
        return $data;
    }
}
