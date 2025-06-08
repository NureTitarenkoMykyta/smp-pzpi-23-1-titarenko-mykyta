#!/bin/bash

create_groups(){
	
	sed '1d; s/^\"[[:space:]]*\([^ ]*\).*$/\1/' "$result" > groups.csv
	if [[ ! $(head -n 1 groups.csv) =~ "-" ]]; then
		return 1
	fi
	sort -t'-' -k3,3n "groups.csv" | uniq > temp.csv && mv temp.csv "groups.csv"
	return 0
}

select_timetable(){
        select var in $(ls TimeTable_??_??_20??.csv | sort -t'_' -k4,4n -k3,3n -k2,2)
        do
                timetable=$var
                break
        done

        format_file
}

select_group(){
	create_groups

	if [[ $? -eq 1 ]]; then
		return 1	
	fi
	select var in $(cat groups.csv)
	do
		group=$var
		break
	done
}

check_existence_of_group(){
	create_groups
	
	if [[ $? -eq 1 ]]; then
        	return 0
	fi

	if grep -q $group "groups.csv"; then
		return 0
	else
		return 1
	fi
}

format_file(){
	iconv -f WINDOWS-1251 -t UTF-8 "$timetable" > "$result"
	sed -i 's/\r/\n/g' "$result"	
}

convert_csv_for_import_in_Google_Calendar(){
awk -v group="$group" -F '\",\"' '

function format_time(time){
        split(time, formatted_time, ":")
        if (formatted_time[1] < 12){
                if (formatted_time[1] == 00){
                        result_time = "12:" formatted_time[2] " AM"
                } else {
                        result_time = formatted_time[1] ":" formatted_time[2] " AM"
                }
        } else {
                if (formatted_time[1] < 13){
                        result_time = formatted_time[1] ":" formatted_time[2] " PM"
                } else {
                        result_time = formatted_time[1] - 12 ":" formatted_time[2] " PM"
                }
        }

        return result_time
}

NR==1 {print "Subject,Start Date,Start Time,End Date,End Time,All Day Event,Description,Location"}
NR!=1 {
        split($1, arr, "[\" ]")

        if (arr[2] == group || arr[3] != "-"){

                split($12, description, " ")

                if (description[2] == "Лб"){
                        lessons[$12] += 0.5
                } else {
                        lessons[$12]++
                }
                split($2, start_date, ".")
                split($4, end_date, ".")

                start_time = format_time($3)
                end_time = format_time($5)

                print $1 "\"," start_date[2] "/" start_date[1] "/" start_date[3] "," start_time "," end_date[2] "/" end_date[1] "/" end_date[3]  "," end_time ",FALSE,\"" $12 ";№" int(lessons[$12] + 0.5) "\""
        }
}
' "$result" > temp.csv && mv temp.csv "$result"

}

if [[ $1 == "--help" ]]; then
	echo "Usage: task2 [академ_група]... [файл_із_cist.csv]..."
	echo "Функція для створення .csv файлу розкладу групи для завантаження в Гугл-календар"
	echo "академ_група - назва академічної групи (шаблон)"
	echo "файл_із_cist.csv — експортований CSV-файл розкладу занять"
	exit
fi

if [[ $1 == "--version" ]]; then
	echo "task2 1.0"
	exit
fi

if [[ $1 == "-q" ]]; then
	quiet=1
fi

if [[ $1 == "--quiet" ]]; then
	quiet=1
fi

group=$1
timetable=$2

result="Google_$timetable"

format_file

if [[ $timetable == "" || $group == "" ]]; then
	select_timetable
	select_group
elif [[ ! -f "$timetable" ]]; then
	echo "Помилка: файл " $timetable " не існує" >&2
        exit 1
elif ! check_existence_of_group; then
	echo "Групи " $group "немає у вибраному файлі. Оберіть іншу групу з переліку:"
        select_group
fi

awk -F '\",\"' '
	{
		split($2, date, ".")
		formatted_date = date[3] date[2] date[1]
		print formatted_date "," $0
	}
' "$result" | sort -t',' -k1,1n | cut -d',' -f2- > temp.csv && mv temp.csv "$result" 

convert_csv_for_import_in_Google_Calendar

if [[ $quiet != 1 ]]; then
        cat < "$result"
fi

rm "groups.csv"
