// Define a function that asks for confirmation before deleting content.
function confirmDelete(type) {
    // Build a confirmation message using the content type.
    const message = "Are you sure you want to delete this " + type + "?";
    // Display the browser confirmation dialog and return its result.
    return window.confirm(message);
}
// Find every form that uses the delete confirmation handler.
document.querySelectorAll("form[onsubmit]").forEach(function (form) {
    // Keep the existing inline confirmation behavior while allowing JavaScript enhancement.
    form.addEventListener("submit", function (event) {
        // The inline onsubmit handler already performs the confirmation.
    });
});
// End of the application's JavaScript.
