#!/bin/bash

print_chars(){
chars_count=$1
local char=$2
local i
    for ((i = 0; i < chars_count; i++)); do
        echo -n "$char"
    done
}


draw_branch_tier(){
    branch_width=3
    height=$1
    char=$2
    while((height != 0)); do

        print_chars $height ' '
        ((height--))

        print_chars branch_width $char
        echo ""
        ((branch_width+=2))
        if [[ "$char" == "#" ]]; then
            char="*"
        else
            char="#"
        fi
    done
}

branch_count=2
trunk_height=2
christmas_tree_size=$1
if [[ $christmas_tree_size -lt 8 ]]; then
    echo "Помилка: мінімальна висота ялинки - 8" >&2
    exit 1
fi
additional_component_size=4
additional_snow_size=$((christmas_tree_size % 2 == 0 ? 3 : 2))
snow_weight=$((christmas_tree_size - additional_component_size + additional_snow_size))
if [[ snow_weight -ne $2 && snow_weight -ne $(($2 - 1)) ]]; then
    echo "Ширина снігу для цього розміру ялинки повинна дорівнювати " $snow_weight >&2
    exit 1
fi
branch_height=$(((christmas_tree_size-additional_component_size)/2 ))

set -f

print_chars $((branch_height + 1)) " "
echo "*"
char='#'
until ((branch_count == 0)); do
    if [[ branch_count -eq 1 && $((branch_height % 2)) -eq 1 ]]; then
        char='*'
    fi
    draw_branch_tier branch_height $char
    ((branch_count--))
done

for i in 1 2; do
    print_chars branch_height " "
    echo "###"
done

print_chars snow_weight '*'
echo ""
set +f