<?php
// cosine_similarity.php

/**
 * Calculate cosine similarity between selected genres and movie genres.
 *
 * @param array $selectedGenres
 * @param array $movieGenres
 * @return float
 */
function calculateCosineSimilarity($selectedGenres, $movieGenres) {
    $intersection = count(array_intersect($selectedGenres, $movieGenres));
    $selectedGenresCount = count($selectedGenres);
    $movieGenresCount = count($movieGenres);

    if ($selectedGenresCount > 0 && $movieGenresCount > 0) {
        return $intersection / (sqrt($selectedGenresCount) * sqrt($movieGenresCount));
    }
    return 0;
}

/**
 * Calculate cosine similarity between selected genres (with frequency) and movie genres.
 *
 * @param array $selectedGenres
 * @param array $movieGenres
 * @param array $genreFrequency
 * @return float
 */
function calculateCosineSimilarityWithFrequency($selectedGenres, $movieGenres, $genreFrequency) {
    $dotProduct = 0;
    $watchlistMagnitude = 0;
    $movieMagnitude = 0;

    // Calculate dot product and magnitudes
    foreach ($selectedGenres as $genreId) {
        $watchlistWeight = $genreFrequency[$genreId] ?? 0; // Weight based on genre frequency
        $watchlistMagnitude += $watchlistWeight ** 2;       // Magnitude for the watchlist

        // If the movie contains this genre, add to the dot product
        if (in_array($genreId, $movieGenres)) {
            $dotProduct += $watchlistWeight;
        }
    }

    // Calculate magnitude for the movie genres (equal weight for all genres in the movie)
    foreach ($movieGenres as $genreId) {
        $movieMagnitude += 1; // Assume each movie genre has equal weight
    }
    $movieMagnitude = sqrt($movieMagnitude);
    $watchlistMagnitude = sqrt($watchlistMagnitude);

    // If magnitudes are non-zero, calculate similarity
    if ($watchlistMagnitude * $movieMagnitude > 0) {
        return $dotProduct / ($watchlistMagnitude * $movieMagnitude);
    }

    return 0; // No similarity if no overlap or zero magnitude
}
?>
