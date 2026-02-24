<?php

$file = 'app/Http/Controllers/ReportController.php';
$content = file_get_contents($file);

// Fix 1: Add $totalNotApplicable calculation after $totalVeryDisagree in calculateUnitMetrics function
$search1 = "        // Total Very Disagree (rate_score = 1)
        \$totalVeryDisagree = \$ratings->where('rate_score', 1)->groupBy('customer_id')->count();       

        // Calculate percentages";

$replace1 = "        // Total Very Disagree (rate_score = 1)
        \$totalVeryDisagree = \$ratings->where('rate_score', 1)->groupBy('customer_id')->count();       

        // Total Not Applicable (rate_score = 6)
        \$totalNotApplicable = \$ratings->where('rate_score', 6)->groupBy('customer_id')->count();

        // Calculate percentage_not_applicable
        \$percentageNotApplicable = 0;
        if (\$totalRespondents > 0) {
            \$percentageNotApplicable = number_format((\$totalNotApplicable / \$totalRespondents) * 100, 2);
        }

        // Calculate percentages";

$content = str_replace($search1, $replace1, $content);

// Fix 2: Change $percentage_not_applicable to $percentageNotApplicable in the return array
$search2 = "            'percentage_not_applicable' => \$percentage_not_applicable,";
$replace2 = "            'percentage_not_applicable' => \$percentageNotApplicable,";

$content = str_replace($search2, $replace2, $content);

// Fix 3: Add 'total_not_applicable' => $totalNotApplicable to the return array
$search3 = "            'total_very_disagree' => \$totalVeryDisagree,
            'percentage_strongly_agree' => \$percentageStronglyAgree,";

$replace3 = "            'total_very_disagree' => \$totalVeryDisagree,
            'total_not_applicable' => \$totalNotApplicable,
            'percentage_strongly_agree' => \$percentageStronglyAgree,";

$content = str_replace($search3, $replace3, $content);

file_put_contents($file, $content);

echo "Fixes applied successfully!\n";

// Verify the changes
$verify = file_get_contents($file);
if (strpos($verify, '\$totalNotApplicable = \$ratings->where(\'rate_score\', 6)') !== false) {
    echo "✓ \$totalNotApplicable calculation added\n";
}
if (strpos($verify, '\$percentageNotApplicable = number_format((\$totalNotApplicable / \$totalRespondents) * 100, 2)') !== false) {
    echo "✓ \$percentageNotApplicable calculation added\n";
}
if (strpos($verify, "'percentage_not_applicable' => \$percentageNotApplicable,") !== false) {
    echo "✓ Return array uses \$percentageNotApplicable\n";
}
if (strpos($verify, "'total_not_applicable' => \$totalNotApplicable,") !== false) {
    echo "✓ Return array includes 'total_not_applicable'\n";
}

?>
