<html>
  <head>
    <script src="https://js.upload.io/uploader/v2"></script>
    <script>
      const uploader = Uploader({
        apiKey: "free" // Replace with your API key.
      });
      uploader.open({ maxFileCount: 1 }).then(
        files => {
          const fileUrls = files.map(x => x.fileUrl).join("\n");
          const success = fileUrls.length === 0 
            ? "No file selected." 
            : `File uploaded:\n\n${fileUrls}`;
          console.log(success);
        },
        error => {
          alert(error);
        }
      );
    </script>
  </head>
  <body></body>
</html>