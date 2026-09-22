<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$products = [
    ["id" => 1, "sku" => "SKU-1001", "name" => "Adjustable Dumbbell Set", "category" => "Equipment", "quantity" => 12, "unit_price" => 89.99, "reorder_level" => 5, "supplier" => "IronForge Supply"],
    ["id" => 2, "sku" => "SKU-1002", "name" => "Olympic Barbell", "category" => "Equipment", "quantity" => 4, "unit_price" => 149.99, "reorder_level" => 5, "supplier" => "IronForge Supply"],
    ["id" => 3, "sku" => "SKU-1003", "name" => "Resistance Bands Set", "category" => "Equipment", "quantity" => 0, "unit_price" => 19.99, "reorder_level" => 15, "supplier" => "IronForge Supply"],
    ["id" => 4, "sku" => "SKU-2001", "name" => "Whey Protein Powder (Chocolate)", "category" => "Supplements", "quantity" => 60, "unit_price" => 34.99, "reorder_level" => 20, "supplier" => "PeakNutrition"],
    ["id" => 5, "sku" => "SKU-2002", "name" => "Pre-Workout Formula", "category" => "Supplements", "quantity" => 10, "unit_price" => 29.99, "reorder_level" => 15, "supplier" => "PeakNutrition"],
    ["id" => 6, "sku" => "SKU-2003", "name" => "BCAA Capsules", "category" => "Supplements", "quantity" => 0, "unit_price" => 18.50, "reorder_level" => 20, "supplier" => "PeakNutrition"],
    ["id" => 7, "sku" => "SKU-3001", "name" => "Gym Tank Top", "category" => "Apparel", "quantity" => 85, "unit_price" => 15.00, "reorder_level" => 25, "supplier" => "ActiveWear Co."],
    ["id" => 8, "sku" => "SKU-3002", "name" => "Compression Leggings", "category" => "Apparel", "quantity" => 18, "unit_price" => 32.00, "reorder_level" => 20, "supplier" => "ActiveWear Co."],
    ["id" => 9, "sku" => "SKU-3003", "name" => "Gym Shorts", "category" => "Apparel", "quantity" => 6, "unit_price" => 20.00, "reorder_level" => 15, "supplier" => "ActiveWear Co."],
    ["id" => 10, "sku" => "SKU-4001", "name" => "Yoga Mat", "category" => "Accessories", "quantity" => 40, "unit_price" => 22.00, "reorder_level" => 10, "supplier" => "FlexFit Gear"],
    ["id" => 11, "sku" => "SKU-4002", "name" => "Shaker Bottle", "category" => "Accessories", "quantity" => 5, "unit_price" => 8.99, "reorder_level" => 10, "supplier" => "FlexFit Gear"],
    ["id" => 12, "sku" => "SKU-4003", "name" => "Weightlifting Gloves", "category" => "Accessories", "quantity" => 30, "unit_price" => 14.50, "reorder_level" => 10, "supplier" => "FlexFit Gear"],
    ["id" => 13, "sku" => "SKU-5001", "name" => "Foam Roller", "category" => "Recovery", "quantity" => 20, "unit_price" => 25.00, "reorder_level" => 8, "supplier" => "RecoverPlus"],
    ["id" => 14, "sku" => "SKU-5002", "name" => "Massage Gun", "category" => "Recovery", "quantity" => 2, "unit_price" => 99.99, "reorder_level" => 5, "supplier" => "RecoverPlus"],
    ["id" => 15, "sku" => "SKU-5003", "name" => "Ice Pack Wrap", "category" => "Recovery", "quantity" => 15, "unit_price" => 12.99, "reorder_level" => 8, "supplier" => "RecoverPlus"]
];

echo json_encode($products);
?>