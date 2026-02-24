<?php
$file = "app/Http/Controllers/ReportController.php";
$content = file_get_contents($file);

// Fix 1: Add totalNotApplicable calculation after totalVeryDisagree in calculateUnitMetrics
$search1 = "// Total Very Disagree (rate_score = 1)\n        \$totalVeryDisagree = \$ratings->where('rate_score', 1)->groupBy('customer_id')->count();\n\n        // Calculate percentages";
$replace1 = "// Total Very Disagree (rate_score = 1)\n        \$totalVeryDisagree = \$ratings->where('rate_score', 1)->groupBy('customer_id')->count();\n\n        // Total Not Applicable (rate_score = 6)\n        \$totalNotApplicable = \$ratings->where('rate_score', 6)->groupBy('customer_id')->count();\n\n        // Calculate percentage_not_applicable\n        \$percentageNotApplicable = 0;\n        if (\$totalRespondents > 0) {\n            \$percentageNotApplicable = number_format((\$totalNotApplicable / \$totalRespondents) * 100, 2);\n        }\n\n        // Calculate percentages";

$content = str_replace($search1, $replace1, $content);

// Fix 2: Fix the undefined variable in return array of calculateUnitMetrics
$search2 = "'percentage_not_applicable' => \$percentage_not_applicable,";
$replace2 = "'percentage_not_applicable' => \$percentageNotApplicable,";
$content = str_replace($search2, $replace2, $content);

file_put_contents($file, $content);
echo "Fix applied successfully!\n";
?>
