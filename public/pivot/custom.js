function loader_notification() {
    //disable notification
    //do nothing
    console.log("loader_notification");

    try {
        $("select").select2();
    } catch (err) {
        // document.getElementById("demo").innerHTML = err.message;
    }
}
