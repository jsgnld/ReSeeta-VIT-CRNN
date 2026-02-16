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
        
        // Merge lexicon corrections into results
        foreach ($results as &$result) {
            $matchingLex = collect($lexicon)->firstWhere('No.', $result['No.']);
            if ($matchingLex) {
                $result['predicted_label_lex'] = $matchingLex['predicted_label'] ?? null;
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
