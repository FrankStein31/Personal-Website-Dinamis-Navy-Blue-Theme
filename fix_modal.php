<?php
$files = ['index.php', 'projects.php'];
foreach($files as $file) {
    $c = file_get_contents($file);
    
    // Fix the CSS layout to be vertical
    $c = preg_replace('/\.modal-split-layout\s*\{.*?\}/s', ".modal-split-layout { display: flex; flex-direction: column; flex-grow: 1; overflow-y: auto; background-color: #050D18; max-height: 75vh; }", $c);
    $c = preg_replace('/\.modal-gallery-pane\s*\{.*?\}/s', ".modal-gallery-pane { position: relative; background: #030A16; min-height: 450px; width: 100%; flex-shrink: 0; }", $c);
    $c = preg_replace('/\.modal-text-pane\s*\{.*?\}/s', ".modal-text-pane { padding: 32px; display: flex; flex-direction: column; background: var(--card-bg); border-top: 1px solid var(--border-steel); }", $c);
    $c = str_replace('var(--bg-card)', 'var(--card-bg)', $c); // Fix var typo if any

    // Also update media query modal-gallery-pane height for responsiveness
    $c = preg_replace('/\.modal-gallery-pane\s*\{\s*height:\s*240px;\s*min-height:\s*240px;\s*\}/s', ".modal-gallery-pane { height: auto; min-height: 300px; }", $c);

    // Fix the JS for body overflow shift
    $c = str_replace("document.body.style.overflow = 'hidden';", "document.body.style.overflow = 'hidden'; document.body.style.paddingRight = (window.innerWidth - document.documentElement.clientWidth) + 'px';", $c);
    $c = str_replace("document.body.style.overflow = 'auto';", "document.body.style.overflow = 'auto'; document.body.style.paddingRight = '0px';", $c);
    
    file_put_contents($file, $c);
}
echo "Layout and bug fixed.\n";
