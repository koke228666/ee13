<?php
$query = trim(strip_tags((string) ($_REQUEST["str"] ?? "")));

$cities = [
    ["Arcadia Bay", "Arcadia Bay"],
];

if ($query !== "") {
    $cities = [[$query, $query]];
}

return json_encode(
    $query !== "" ? $cities : ["cities" => $cities],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);