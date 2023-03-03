<?php

// Generate csrf token
$csrfToken = \Drupal::csrfToken()->get();
// DdvZh9DnwJo51PAAH56JgzN8o4akbRIajd_d3cLa4l4
// Valiate token
\Drupal::csrfToken()->validate($csrfToken);
// 1 if ok
