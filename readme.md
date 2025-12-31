Trong WordPress, bạn cần Enqueue file CSS đã được compile.

Khi Dev (Watch mode): Bạn chạy lệnh: npx tailwindcss -i ./assets/css/tailwind.css -o ./assets/css/main.css --watch

Trong functions.php: Bạn đã có dòng này từ bước trước, nó sẽ đọc file main.css mà Tailwind vừa build ra: