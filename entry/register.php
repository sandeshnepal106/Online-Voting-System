<?php
include "../header.php";
?>
<body>
    <div class="flex justify-center items-center h-screen">
        <form action="login.php" class="bg-[#0C969C] flex flex-col items-center justify-center self-center w-1/5 p-10 rounded-lg space-y-4">
            <div>
                <input type="text" name="fullname" placeholder="Full Name" class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <div>
                <input type="text" name="username" placeholder="Username" class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <div>
                <input type="tel" name="contact" placeholder="Contact No." class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <div>
                <input type="email" name="email" placeholder="Email" class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <div>
                <input type="password" name="password" placeholder="Password" class="w-full border border-gray-300 p-1 bg-white rounded-lg">
            </div>
            <button type="button"><a href="">Register</a></button>
            <button type="button"><a href="login.php">Already have an account?</a></button>
        </form>
    </div>
        
</body>