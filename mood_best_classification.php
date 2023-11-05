<?php

require "vendor/autoload.php";

use Phpml\Classification\SVC;
use Phpml\Classification\KNearestNeighbors;
use Phpml\Classification\NaiveBayes;
use Phpml\CrossValidation\RandomSplit;
use Phpml\Dataset\CsvDataset;
use Phpml\Metric\Accuracy;
use Phpml\ModelManager;

// Wczytaj dane
$data = new CsvDataset('./data/mood_data.csv', 4, true);

// Podział na dane treningowe i testowe
$dataset = new RandomSplit($data, 0.2);

// Inicjalizacja i uczenie różnych modeli
$models = [
    'SVC' => new SVC(),
    'k-Nearest Neighbors' => new KNearestNeighbors(3),
    'Naive Bayes' => new NaiveBayes(),
];

foreach ($models as $modelName => $model) {
    $model->train($dataset->getTrainSamples(), $dataset->getTrainLabels());

    // Przewidywanie nastroju na danych testowych
    $predicted = $model->predict($dataset->getTestSamples());

    // Ewaluacja modelu za pomocą metryki Accuracy
    $accuracy = Accuracy::score($dataset->getTestLabels(), $predicted);
    echo "$modelName Accuracy: " . $accuracy . PHP_EOL;

    // Zapisz model do pliku
    $modelManager = new ModelManager();
    $modelManager->saveToFile($model, "./models/$modelName");
}

?>