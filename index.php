<!DOCTYPE html>
<html>
    <head>
        <title>Contact Form</title>
    </head>
    <body>
        <h2>Contact</h2>
        <form action="process_form.php" method="post">
            <label for="full_name"> Full Name :</label>
            <input type="text" name="full_name" id="full_name" value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>" required><br>
            <label for="phone_number"> Phone Number :</label>
            <input type="tel" name="phone_number" id="phone_number" value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>" required><br>
            <label for="email"> Email :</label>
            <input type="email" name="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"required><br>
            <label for="subject"> Subject :</label>
            <input type="text" name="subject" id="subject" value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>"required><br>
            <label for="message"> Message :</label>
            <input type="textarea" name="message" id="message" value="<?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?>"required><br>

            <input type="submit" value="Submit">
        </form>
    </body>
</html>