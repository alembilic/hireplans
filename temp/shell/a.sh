#!/bin/bash

# Set the source and destination directories
source_dir="/opt/cribl"
dest_dir="/app/cribl"

# Function to recursively find SELinux contexts
find_contexts() {
  find "$1" -type f -print0 | xargs -0 ls -Z | awk '{print $2}'  > /tmp/selinux_contexts.txt
}

# Function to copy files with their SELinux contexts
copy_with_contexts() {
  while IFS= read -r context; do
    find "$source_dir" -type f -print0 | xargs -0 sh -c '
      file="$1"; context="$2"; target_dir="$3";
      cp -p "$file" "$target_dir";
      chcon "$context" "$target_dir/$(basename "$file")";
    ' -- "$1" "$context" "$target_dir"
  done < /tmp/selinux_contexts.txt
}

# Find SELinux contexts in the source directory
find_contexts "$source_dir"

# Copy files with their SELinux contexts
copy_with_contexts "$source_dir" "$dest_dir"

# Clean up temporary file
rm -f /tmp/selinux_contexts.txt
