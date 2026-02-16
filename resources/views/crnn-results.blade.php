<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CRNN Results - ReSeeta</title>
  <link rel="stylesheet" href="{{ asset('css/results.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
</head>
<body>
  <nav class="top-nav">
    <a href="{{ url('/') }}" class="{{ Request::is('/') ? 'active' : '' }}">Home</a>
    <a href="{{ url('/about') }}" class="{{ Request::is('about') ? 'active' : '' }}">About</a>
    <a href="{{ url('/vit-crnn-results') }}" class="{{ Request::is('vit-crnn-results') ? 'active' : '' }}">ViT-CRNN Results</a>
    <a href="{{ url('/crnn-results') }}" class="{{ Request::is('crnn-results') ? 'active' : '' }}">CRNN Results</a>
  </nav>

  <main class="results">
    <header class="results-hero">
      <p class="eyebrow">Baseline Results</p>
      <h1>CRNN Results</h1>
      <p class="subtitle">Baseline recognition results for the CRNN-only model on the Combined Test Set.</p>
    </header>

    <section class="results-stats">
      <div class="stat-card">
        <div class="stat-label">Total Samples</div>
        <div class="stat-value">{{ count($results) }}</div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Correct</div>
        <div class="stat-value stat-correct">
          {{ collect($results)->filter(fn($r) => $r['ground_truth'] === $r['predicted_label'])->count() }}
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-label">Accuracy</div>
        <div class="stat-value">
          {{ count($results) > 0 ? round((collect($results)->filter(fn($r) => $r['ground_truth'] === $r['predicted_label'])->count() / count($results)) * 100, 2) : 0 }}%
        </div>
      </div>
    </section>

    <section class="results-grid">
      @foreach($results as $result)
        @php
          $isCorrect = $result['ground_truth'] === $result['predicted_label'];
          $rowNum = intval($result['No.']);
          // For rows 1-288: image_name is 'test/dt[0]', add .png
          // For rows 289+: image_name is 'filename.png', prepend Processed_New/
          if ($rowNum >= 1 && $rowNum <= 288) {
            $imagePath = 'Results/' . $result['image_name'] . '.png';
          } else {
            $imagePath = 'Results/Processed_New/' . $result['image_name'];
          }
        @endphp
        <article class="result-card {{ $isCorrect ? 'correct' : 'incorrect' }}">
          <div class="card-header">
            <span class="status-badge {{ $isCorrect ? 'badge-correct' : 'badge-incorrect' }}">
              {{ $isCorrect ? 'Correct' : 'Wrong' }}
            </span>
          </div>
          
          <div class="card-image">
            @if(file_exists(public_path($imagePath)))
              <img src="{{ asset($imagePath) }}" alt="{{ $result['image_name'] }}" title="{{ $result['image_name'] }}">
            @else
              <div class="no-image" title="{{ $result['image_name'] }}">No image available</div>
            @endif
            <div class="image-filename">{{ $result['image_name'] }}</div>
          </div>
          
          <div class="card-content">
            <div class="card-row">
              <div class="card-label">Ground Truth</div>
              <div class="card-value">{{ $result['ground_truth'] }}</div>
            </div>
            
            <div class="card-row">
              <div class="card-label">Predicted</div>
              <div class="card-value">{{ $result['predicted_label'] }}</div>
            </div>
          </div>
        </article>
      @endforeach
    </section>
  </main>

  <footer>
    <p>© {{ date('Y') }} ReSeeta. All Rights Reserved.</p>
  </footer>
</body>
</html>
