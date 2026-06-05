<?php
$c = file_get_contents('projects.php'); 
$c = preg_replace('/}\r?\n}\r?\n\r?\n\s*\.btn-internal-primary:hover/', "}\r\n\r\n        .btn-internal-primary:hover", $c); 
$append = "\n        .back-btn-container { display: flex; justify-content: flex-start; margin-bottom: 30px; }\n        .btn-back { display: inline-flex; align-items: center; gap: 8px; color: var(--text-muted); text-decoration: none; font-weight: 700; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; transition: all 0.3s ease; padding: 10px 20px; border: 1px solid var(--border-steel); border-radius: 8px; }\n        .btn-back:hover { color: var(--text-main); border-color: var(--text-main); transform: translateX(-5px); background: rgba(31,78,107,0.1); }\n";
$c = str_replace('.portfolio-cta { text-align: center; }', '.portfolio-cta { text-align: center; }'.$append, $c);
file_put_contents('projects.php', $c);
echo "Done CSS fixes projects.php\n";
