<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    <form id="file-upload-form" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file">
        <button type="submit">Upload</button>
    </form>
    
    <script>
        const form = document.querySelector('#file-upload-form');
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            
            const formData = new FormData(form);
            const url = 'https://ecdc-14-169-248-136.ap.ngrok.io/api/process-file';
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    // headers: {
                    //     'Authorization': 'Bearer <token>'
                    // }
                });
                const json = await response.json();
                console.log(json);
                
            } catch (error) {
                console.error(error);
            }
        });
    </script>
</body>
</html>

