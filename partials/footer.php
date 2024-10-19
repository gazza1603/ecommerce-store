<footer>
    <p>&copy; 2024 Shadow Wood. All Rights Reserved.</p>
</footer>

<button onclick="scrollToTop()" id="scrollBtn" style="display: none;">Top</button>

<script>

    window.onscroll = function() {
        let scrollBtn = document.getElementById('scrollBtn');
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            scrollBtn.style.display = 'block';
        } else {
            scrollBtn.style.display = 'none';
        }
    };


    function scrollToTop() {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>

<style>
    #scrollBtn {
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 10px 20px;
        font-size: 18px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #scrollBtn:hover {
        background-color: #0056b3;
    }
</style>
