<?php
return [
    'login_title' => 'Login',
    'email' => 'Email',
    'password' => 'Password',
    'login_button' => 'Log In',
    'no_account' => "Don't have an account? Register",
    'invalid_login' => 'Incorrect email or password!',
    'register_title' => 'Register',
    'register_button' => 'Register',
    'success_register' => 'Registration successful! Log in',
    'already_have_account' => 'Already have an account? Log in',
    'welcome' => 'Welcome',
    'logout' => 'Logout',

    'pdf_tools' => 'PDF Tools',
    'merge_pdf_files' => 'Merge PDF Files',
    'select_files_to_merge' => 'Select PDF files to merge:',
    'merge' => 'Merge',
    'split_pdf' => 'Split PDF',
    'choose_pdf_file' => 'Choose PDF file:',
    'pages_to_extract' => 'Pages to extract (e.g. 1-2,4,6):',
    'split' => 'Split',
    'compress_pdf' => 'Compress PDF',
    'choose_pdf_to_compress' => 'Choose PDF to compress:',
    'compress' => 'Compress',
    'coming_soon' => 'Coming soon',
    'merge_pdf' => 'Merge PDF',

    'error_register' => 'Registration failed. Please try again.',

    'view_pdf_info' => 'View PDF Info',
    'pdf_metadata_viewer' => 'PDF Metadata Viewer',
    'select_pdf_file' => 'Select PDF file',
    'view_info' => 'View Info',

    'history' => 'History',
    'manual' => 'Manual',

    'title' => 'Admin History Log',
    'back' => 'Back',
    'action' => 'Action',
    'user' => 'User',
    'ip' => 'IP',
    'timestamp' => 'Timestamp',
    'delete' => 'Delete',
    'no_history' => 'No history yet.',
    'confirm_delete' => 'Are you sure you want to delete this entry?',


    //for manual
    'user_manual_html' => <<<HTML
<h2>User Guide</h2>
<p>The application allows users to edit PDF files directly through the web interface (frontend) or via API requests.</p>

<h3>Frontend (Graphical Interface)</h3>
<p>After logging in, the user is redirected to the main page with an overview of available tools:</p>
<ul>
  <li><strong>Merge PDF</strong> – merges multiple PDFs into one</li>
  <li><strong>Split PDF</strong> – splits PDF into individual pages</li>
  <li><strong>Compress PDF</strong> – reduces PDF size</li>
  <li><strong>PDF Metadata</strong> – displays technical information about the file (page count, author, etc.)</li>
</ul>

<p>Each tool is accessed via a modal window – clicking the button opens a dialog to upload a file and perform the action.</p>

<h3>API Interface</h3>
<p>The application provides a simple API for individual functions. Requests are sent using <code>POST</code> to specific endpoints, such as:</p>
<ul>
  <li><code>api/merge.php</code> – PDF merging</li>
  <li><code>api/split.php</code> – PDF splitting</li>
  <li><code>api/compress.php</code> – compression</li>
  <li><code>api/pdfinfo.php</code> – displaying information</li>
</ul>

<p>The API requires <code>multipart/form-data</code> form submissions with an uploaded <code>file</code> and, if needed, additional parameters (e.g., <code>pages</code> for split/remove).</p>


HTML


];
