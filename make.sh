#!/bin/bash


ITER=0

# USAGE=$(cat ./pages.txt)

USAGE=$(cat <<-END
International Workshop on African Vultures and Poison
International Seminar on Lead poisoning and Bearded Vultures
Cinereous Vulture Meeting 2014
Annual Bearded Vulture Meeting 2014
Annual Bearded Vulture Meeting 2015
Annual Bearded Vulture Meeting 2016
Annual Bearded Vulture Meeting 2017
Annual Bearded Vulture Meeting 2018
END
)



# https://linuxhandbook.com/bash-split-string/
# Splits lines into array
IFS=$'\n' read -d '' -a pages <<< "$USAGE"

# Create main pages
for i in "${pages[@]}"
do
  ITER=$(expr $ITER + 1)

  # MAIN PAGES
  POSTTYPE="event"

  # CURRENT=$(wp post create --post_type=${POSTTYPE} --post_title="$i" --menu_order=${ITER} --post_status='publish' --porcelain )
  # wp menu item add-post primary-navigation $CURRENT
  # wp menu item add-post footer-navigation $CURRENT

  # SUBPAGES
  # Add the generated parent ID for the sub pages
  # PARENT=214
  # CURRENT=$(wp post create --post_type=${POSTTYPE} --post_title="$i" --menu_order=${ITER} --post_status='publish' --post_parent=${PARENT} --porcelain )

  # CUSTOM POST TYPES
  # Remember to change the post type variable
  CURRENT=$(wp post create --post_type=${POSTTYPE} --post_title="$i" --menu_order=${ITER} --post_status='publish' --post_category=7 --porcelain )

  echo "Added new ${POSTTYPE} > '$i' id: $CURRENT"
done