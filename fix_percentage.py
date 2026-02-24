#!/usr/bin/env python3

import re

file_path = 'app/Http/Controllers/ReportController.php'

with open(file_path, 'r') as f:
    content = f.read()

# Fix 1: Add $totalNotApplicable calculation after $totalVeryDisagree in calculateUnitMetrics
old_pattern1 = """        // Total Very Disagree (rate_score = 1)
        $totalVeryDisagree = $ratings->where('rate_score', 1)->groupBy('customer_id')->count();

        // Calculate percentages"""

new_pattern1 = """        // Total Very Disagree (rate_score = 1)
        $totalVeryDisagree = $ratings->where('rate_score', 1)->groupBy('customer_id')->count();

        // Total Not Applicable (rate_score = 6)
        $totalNotApplicable = $ratings->where('rate_score', 6)->groupBy('customer_id')->count();

        // Calculate percentages"""

content = content.replace(old_pattern1, new_pattern1)

# Fix 2: Add $percentageNotApplicable calculation after $percentageDetractors
old_pattern2 = """        $percentageDetractors = 0;
        if ($totalRespondents > 0) {
            $percentageDetractors = number_format(($totalDetractors / $totalRespondents) * 100, 2);
        }

        // Calculate raw percentages"""

new_pattern2 = """        $percentageDetractors = 0;
        if ($totalRespondents > 0) {
            $percentageDetractors = number_format(($totalDetractors / $totalRespondents) * 100, 2);
        }

        // Calculate percentage_not_applicable
        $percentageNotApplicable = 0;
        if ($totalRespondents > 0) {
            $percentageNotApplicable = number_format(($totalNotApplicable / $totalRespondents) * 100, 2);
        }

        // Calculate raw percentages"""

content = content.replace(old_pattern2, new_pattern2)

# Fix 3: Fix the return array to use correct variable name and add total_not_applicable
old_pattern3 = """            'percentage_not_applicable' => $percentage_not_applicable,
            'nps' => $nps,
        ];
    }

    /**
     * Calculate metrics for a specific unit with month/year filter
     */"""

new_pattern3 = """            'total_not_applicable' => $totalNotApplicable,
            'percentage_not_applicable' => $percentageNotApplicable,
            'nps' => $nps,
        ];
    }

    /**
     * Calculate metrics for a specific unit with month/year filter
     */"""

content = content.replace(old_pattern3, new_pattern3)

with open(file_path, 'w') as f:
    f.write(content)

print("Fixes applied successfully!")

