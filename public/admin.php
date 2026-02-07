<?php include("db_connect.php"); ?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Panel - Email Registrations</title>
<link rel="stylesheet" href="_admin.css" type="text/css">
<script src="https://kit.fontawesome.com/06279fadeb.js" crossorigin="anonymous"></script>
<style>

</style>
</head>
<body>
<table>
<tr style="background-color: #f0e4e4;">
<td width="220" style="border:0px;"><img src="https://post.nu/post-nu-blue.png" width="220"></td>    
<td style="border:0px;"><h2>Godk&auml;nda anv&auml;ndare</h2></td>
<td style="border:0px;">
<form action="" method="post"> 
<input type="text" name="search" size="8" value="<?php echo isset($_POST['search']) ? htmlspecialchars($_POST['search']) : ''; ?>" />
<input type="submit" name="submit" value="Search" /> 
</form>     
</td>
</tr>
</table>
<table> 
<tr>
<td style="padding: 10px;background: #f9f4f4;">
<?php include('_admin_menu.php');?>
</td>
</tr>
</table>
<table>
<tr>
<th>ID</th>
<th>Namn</th>
<th>Stad</th>
<th>Land</th>
<th>Nuvarande Email</th>
<th>Reg. IP</th>
<th>Full Önskad Email</th>
<th>Action</th>
</tr>

<?php
mysqli_set_charset($conn, "utf8");

$sql = "SELECT * FROM email_registrations WHERE verifierad = '1' AND res = '0'";

if (isset($_POST['submit']) && !empty(trim($_POST['search']))) {
    $search = $conn->real_escape_string(trim($_POST['search']));
    $sql .= " AND (namn LIKE '%$search%' 
               OR stad LIKE '%$search%' 
               OR land LIKE '%$search%' 
               OR nuvarande_email LIKE '%$search%' 
               OR full_onskad_email LIKE '%$search%' 
               OR ip LIKE '%$search%')";
}

$sql .= " ORDER BY id DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {

$pass = $row['pw'];            
//nouveaumessage = nouveaumessage.replace('+', '%2B').replace('&', '%26');
$pass = str_replace('&', '%26', $pass);

    echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['namn']}</td>
            <td>{$row['stad']}</td>
            <td>{$row['land']}</td>
            <td>{$row['nuvarande_email']}</td>
            <td><a href='https://whatismyipaddress.com/ip/{$row['ip']}' target='_blank'>{$row['ip']}</a></td>
            <td>{$row['full_onskad_email']}</td>
            <td>


                 <a href='edit.php?id={$row['id']}' title='Edit'><i class='fa-regular fa-pen-to-square'></i></a> | 
                <a href='delete_email.php?email={$row['full_onskad_email']}' title='Delete'><i class='fa-regular fa-thumbs-down'></i></a> | 
                <a href='generera.php?regmail={$row['full_onskad_email']}' title='Control Panel'><i class='fa-solid fa-indent'></i></a> | 
                <a href='mailto:{$row['nuvarande_email']}
?subject=Post.nu - Postmaster&body=Hello {$row['namn']}!
%0D%0A%0D%0A
Here below is your auto generated password for the email address {$row['full_onskad_email']}.
%0D%0A%0D%0A Please keep your login credential safe! 
%0D%0A%0D%0A
$pass
%0D%0A%0D%0A
' title='Send password'><i class='fa-solid fa-key'></i></a>
            </td>
          </tr>";
}
?>
</table>
</body>
</html>

