<?php $__env->startSection('content'); ?>
    <style>
        .square-container a {
            text-decoration: none;
        }

        [id^="q"] {
            scroll-margin-top: 200px;
        }

        .incorrect .form-check-label {
            background-color: transparent;
            border-radius: 4px;
        }

        .show-bg.correct .form-check-label {
            background-color: #a5e8b4;
            border-radius: 5px;
        }

        .show-bg.incorrect.selected .form-check-label {
            background-color: #efb2b7;
            border-radius: 5px;
        }

        .btn-start {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            text-align: center;
            font-weight: bold;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .btn-start:hover {
            background-color: #218838;
        }

        .btn-container {
            margin-bottom: 300px;
        }

        .hidden {
            display: none;
        }

        #question-container,
        #result-container {
            width: 60%;
            margin: 0 auto;
        }

        .square-container {
            height: 140px;
            overflow-y: auto;
            border: 1px solid #ccc;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 5px;
            width: 100%;
            margin: 0 auto;
            overflow-x: auto;
        }

        .square {
            width: 30px;
            height: 30px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: white;
            border: 2px solid grey;
            text-decoration: none;
            font-weight: bold;
            color: black;
            transition: background-color 0.3s;
        }

        .square:hover {
            background-color: #f8f9fa;
        }

        .square.selected {
            background-color: #28a745;
            color: white;
        }

        .review-button.hidden {
            display: none;
            /* Ẩn nút "Xem lại" */
        }

        .square.reviewed {
            background-color: #ffc107;
            color: white;
        }

        #timer {
            font-weight: bold;
            font-size: 24px;
            position: fixed;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            z-index: 1000;
            background-color: #fff;
            padding: 10px 20px;
            border: 2px solid #ddd;
            border-radius: 5px;
        }

        .card-body {
            text-align: left;
        }

        .form-check-label {
            text-align: left;
        }

        .mb-4 {
            margin-bottom: 20px;
        }

        #question-container {
            padding-left: 0;
        }

        .square.active {
            background-color: green;
        }


        #result-container h4,
        p {
            text-align: left;
        }

        .table th {
            background-color: #28a745;
            color: white;
        }

        .table td {
            background-color: #d4edda;
        }
    </style>

    <section class="section">
        <div class="section-header">
            <h1><?php echo e($pageTitle); ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="/admin/"><?php echo e(trans('admin/main.dashboard')); ?></a>
                </div>
                <div class="breadcrumb-item"><?php echo e($pageTitle); ?></div>
            </div>
        </div>

        

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="container mt-5">
                                <h1 class="text-center mb-4">Thi thử N1 (Đề mẫu)</h1>
                                <div id="content">
                                    <div class="text-center mt-4">
                                        <img style="max-width: 50%; height: auto;"
                                            src="https://japanesequizzes.com/wp-content/uploads/2016/06/are-you-ready.jpg" alt="Are you ready?"
                                            class="img-fluid w-75 mb-2">
                                        <div class="fw-bold">では、はじめます！ </div>
                                    </div>
                                    <div class="text-center mt-4 btn-container">
                                        <a href="#" class="btn-start text-decoration-none text-white"
                                            id="startButton">Bắt đầu kiểm tra</a>
                                    </div>
                                </div>

                                <div id="question-container" class="hidden text-center mt-5">
                                    <h4 class="text-center" id="timer">Time limit: 02:50:00</h4>
                                    <div class="square-container">
                                        <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <a href="#q<?php echo e($question->id); ?>" class="square"
                                                id="square-link-<?php echo e($question->id); ?>"><?php echo e($question->id); ?></a>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <div style="display: flex; padding-top: 5px;">
                                        <div
                                            style="width: 12px; height: 12px; background-color: #28a745; margin-right: 10px; margin-top: 4px;">
                                        </div>
                                        Câu đã trả lời
                                        <div
                                            style="width: 12px; height: 12px; background-color: #ffc107; margin-left: 10px; margin-right: 10px; margin-top: 4px;">
                                        </div>
                                        xem lại
                                    </div>
                                    <hr>
                                    <img src="https://japanesequizzes.com/wp-content/uploads/2019/07/anh-dokkai-n1.png" alt="" width="100%">
                                    <div class="card mb-4">
                                        <div class="card-body">
                                            <form action="/submit" method="post">
                                                
                                                <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <div id="page<?php echo e($loop->iteration); ?>"
                                                        class="page<?php echo e($loop->first ? '' : ' d-none'); ?>">
                                                        <h2>Nhóm: <?php echo e($group->title); ?> - <?php echo e($group->info); ?></h2>
                                                        <div class="card-body">
                                                            <?php $__currentLoopData = $questions->where('group_id', $group->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="mb-4 question" id="q<?php echo e($question->id); ?>">
                                                                    <p><strong>Câu hỏi #<?php echo e($question->id); ?>:</strong>
                                                                        
                                                                        <?php echo e($question->content); ?></p>
                                                                    <?php $__currentLoopData = $answers->where('question_id', $question->id); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $answer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div
                                                                            class="form-check<?php echo e($answer->is_correct ? ' correct' : ' incorrect'); ?>">
                                                                            <input class="form-check-input" type="radio"
                                                                                name="q<?php echo e($question->id); ?>"
                                                                                id="q<?php echo e($question->id); ?>a<?php echo e($loop->iteration); ?>"
                                                                                value="<?php echo e($answer->id); ?>">
                                                                            <label class="form-check-label"
                                                                                for="q<?php echo e($question->id); ?>a<?php echo e($loop->iteration); ?>"><?php echo e($loop->iteration); ?>.
                                                                                <?php echo e($answer->content); ?></label>
                                                                        </div>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    <div class="review-button btn btn-primary"
                                                                        data-question="<?php echo e($question->id); ?>">
                                                                        Xem lại
                                                                    </div>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <!-- Phân trang -->
                                                <div class="pagination">
                                                    <button type="button" onclick="prevPage()"
                                                        class="btn btn-success">Previous</button>&ensp;
                                                    <button type="button" onclick="nextPage()"
                                                        class="btn btn-success">Next</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4 btn-container">
                                        <a href="#" class="btn-start text-decoration-none text-white"
                                            id="endButton">Nộp bài</a>
                                    </div>
                                </div>

                                <div id="result-container" class="hidden text-center mt-5">
                                    <h4>Kết quả</h4>
                                    <p>26/107 câu trả lời đúng</p>
                                    <p id="showTime">Tổng thời gian: 00:00:00</p>
                                    <div>
                                        <h4 class="text-center">Bạn đã đạt được 26/107 điểm</h4>
                                        <div class="container"
                                            style="width: 80%; text-align: center; border: 1px solid gray; padding: 10px;">
                                            <div class="d-flex flex-column" style="font-size: 14px; margin-top: 5px;">
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="me-3" style="flex-basis: 30%;">Trung bình:
                                                        38.53%</span>
                                                    <div class="bg-secondary"
                                                        style="height: 20px; flex-grow: 1; width: 70%;">
                                                        <div class="bg-warning" style="width: 38.53%; height: 100%;">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <span class="me-3" style="flex-basis: 30%;">Bạn được: 24.3%</span>
                                                    <div class="bg-secondary"
                                                        style="height: 20px; flex-grow: 1; width: 70%;">
                                                        <div class="bg-success" style="width: 24.3%; height: 100%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <br>
                                    <h4>Categories</h4>
                                    <ul style="list-style-type: none; padding-left: 0; margin-left: 0;">
                                        <li style="display: flex; justify-content: space-between;">
                                            <span>1. Vocabulary</span>
                                            <span style="flex-grow: 1; text-align: center;"></span>
                                            <span>32%</span>
                                        </li>
                                        <li style="display: flex; justify-content: space-between;">
                                            <span>2. Grammar</span>
                                            <span style="flex-grow: 1; text-align: center;"></span>
                                            <span>25%</span>
                                        </li>
                                        <li style="display: flex; justify-content: space-between;">
                                            <span>3. Reading</span>
                                            <span style="flex-grow: 1; text-align: center;"></span>
                                            <span>20%</span>
                                        </li>
                                        <li style="display: flex; justify-content: space-between;">
                                            <span>4. Listening</span>
                                            <span style="flex-grow: 1; text-align: center;"></span>
                                            <span>21.62%</span>
                                        </li>
                                    </ul>

                                    <div
                                        style="background-color: #add8e6; color: white; padding: 10px; width: 100%; border-radius: 5px;">
                                        <span style="font-weight: bold;">Comment:</span>
                                        <div
                                            style="margin-top: 5px; background-color: white; color: black; padding: 5px; border-radius: 5px;">
                                            Very bad! It's Not Funny Anymore. Keep practicing!
                                        </div>
                                    </div><br><br>

                                    <table class="table table-bordered w-100">
                                        <thead>
                                            <tr>
                                                <th>Top</th>
                                                <th>Tên</th>
                                                <th>Làm lúc</th>
                                                <th>Điểm</th>
                                                <th>Kết quả</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>1</td>
                                                <td>Đào lê</td>
                                                <td>2023/01/13 1:55 chiều</td>
                                                <td>92</td>
                                                <td>95,98%</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Nguyễn An</td>
                                                <td>2023/02/20 3:10 chiều</td>
                                                <td>88</td>
                                                <td>90,12%</td>
                                            </tr>
                                            <tr>
                                                <td>3</td>
                                                <td>Trần Minh</td>
                                                <td>2023/03/05 9:30 sáng</td>
                                                <td>85</td>
                                                <td>89,76%</td>
                                            </tr>
                                            <tr>
                                                <td>4</td>
                                                <td>Lê Mai</td>
                                                <td>2023/04/17 11:20 sáng</td>
                                                <td>78</td>
                                                <td>84,13%</td>
                                            </tr>
                                            <tr>
                                                <td>5</td>
                                                <td>Phan Quỳnh</td>
                                                <td>2023/05/30 2:45 chiều</td>
                                                <td>80</td>
                                                <td>86,25%</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div style="margin-bottom: 100px;">
                                        <button class="btn btn-success" onclick="location.reload();">Làm lại</button>
                                        <button class="btn btn-success" id="show-results">Xem lại toàn bộ</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <script>
        document.getElementById("startButton").addEventListener("click", function(event) {
            event.preventDefault(); // Ngừng hành động mặc định của link
            startTimer();

            document.getElementById("content").classList.add("hidden");
            document.getElementById("question-container").classList.remove("hidden");
        });

        document.getElementById("endButton").addEventListener("click", function(event) {
            event.preventDefault();
            endTimer();

            document.getElementById("question-container").classList.add("hidden");
            document.getElementById("result-container").classList.remove("hidden");

            // Duyệt qua từng câu hỏi
            const questions = document.querySelectorAll(".question");

            questions.forEach(function(question) {
                // Lấy tất cả các đáp án trong câu hỏi hiện tại
                const options = question.querySelectorAll('.form-check');
                const selectedOption = question.querySelector(
                    '.form-check-input:checked');
                if (selectedOption) {
                    // Thêm màu xanh cho đáp án đúng
                    question.querySelectorAll('.form-check.correct').forEach(correctOption => {
                        correctOption.classList.add('show-bg');
                    });

                    // Nếu chọn sai, tô đỏ đáp án đã chọn
                    if (selectedOption.closest('.form-check').classList.contains('incorrect')) {
                        selectedOption.closest('.form-check').classList.add('show-bg', 'selected');
                    }
                }
            });

            // Ẩn nút "Nộp" sau khi đã nộp bài
            document.getElementById('endButton').style.display = 'none';
        });

        document.getElementById("show-results").addEventListener("click", function(event) {
            event.preventDefault();

            // Quay lại phần câu hỏi
            document.getElementById("result-container").classList.add("hidden");
            document.getElementById("question-container").classList.remove("hidden");

            // Ẩn đồng hồ khi quay lại
            document.getElementById("timer").style.display = "none";
        });
    </script>
    <script>
        let timerInterval;
        let startTime;
        let remainingTime = 2 * 60 * 60 + 50 * 60;

        // Lấy thời gian đã làm
        const endTime = new Date();
        const timeTaken = (endTime - startTime) / 1000;
        const hours = Math.floor(timeTaken / 3600);
        const minutes = Math.floor((timeTaken % 3600) / 60);
        const seconds = Math.floor(timeTaken % 60);

        // Hàm format thời gian
        function formatTime(seconds) {
            const hours = String(Math.floor(seconds / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((seconds % 3600) / 60)).padStart(2, '0');
            const secs = String(seconds % 60).padStart(2, '0');
            return `${hours}:${minutes}:${secs}`;
        }

        // Hàm bắt đầu đếm ngược
        function startTimer() {
            startTime = Date.now();
            timerInterval = setInterval(() => {
                remainingTime--;
                document.getElementById("timer").textContent =
                    `Time limit: ${formatTime(remainingTime)}`;

                if (remainingTime <= 0) {
                    clearInterval(timerInterval);
                    alert("Hết thời gian!");
                }
            }, 1000);
        }

        // Hàm kết thúc đếm ngược
        function endTimer() {
            clearInterval(timerInterval);
            const endTime = Date.now();
            const elapsedTime = Math.floor((endTime - startTime) / 1000); // Tính thời gian đã làm
            console.log(`Thời gian đã làm: ${formatTime(elapsedTime)}`);
            document.getElementById("showTime").innerText = `Thời gian đã làm: ${formatTime(elapsedTime)}`;
        }
    </script>
    <script>
        let currentPage = 1;
        const totalPages = document.querySelectorAll('.page').length;

        // chuyển sang trang tiếp theo
        function nextPage() {
            if (currentPage < totalPages) {
                document.getElementById("page" + currentPage).classList.add('d-none');
                currentPage++;
                document.getElementById("page" + currentPage).classList.remove('d-none');
                console.log("Next Page: Hiện đang ở trang " + currentPage);
            }
        }

        // quay lại trang trước
        function prevPage() {
            if (currentPage > 1) {
                document.getElementById("page" + currentPage).classList.add('d-none');
                currentPage--;
                document.getElementById("page" + currentPage).classList.remove('d-none');
                console.log("Previous Page: Hiện đang ở trang " + currentPage);
            }
        }

        // Lấy tất cả các liên kết
        const squareLinks = document.querySelectorAll('.square');
        const pages = document.querySelectorAll('.page');

        // Hàm để hiển thị trang chứa câu hỏi và ẩn các trang khác
        function showPageForQuestion(questionId) {
            pages.forEach(page => page.classList.add('d-none'));
            const questionElement = document.querySelector(questionId);
            if (questionElement) {
                const pageToShow = questionElement.closest('.page');
                pageToShow.classList.remove('d-none');

                const pageId = pageToShow.id.replace('page', '');
                currentPage = parseInt(pageId, 10);
                console.log("Previous Page: Hiện đang ở trang " + currentPage);

                questionElement.scrollIntoView({
                    behavior: 'smooth'
                }); // Cuộn đến câu hỏi
            }
        }

        // Thêm sự kiện cho các liên kết
        squareLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const targetId = this.getAttribute('href');
                showPageForQuestion(targetId);
            });
        });

        // Tô màu khi câu hỏi đã được chọn
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', function() {
                const questionNumber = this.name.replace('q', '');
                const squareLink = document.querySelector(`#square-link-${questionNumber}`);

                // Tô màu câu hỏi đã chọn
                squareLink.classList.add('selected');

                // Kiểm tra nếu có class 'reviewed', thì xóa đi
                if (squareLink.classList.contains('reviewed')) {
                    squareLink.classList.remove('reviewed');
                }

                // Ẩn nút "Xem lại" khi đáp án được chọn
                // document.querySelector(`#q${questionNumber} .review-button`).classList.add('hidden');
            });
        });



        // Tô màu cam khi nhấn nút "Xem lại"
        document.querySelectorAll('.review-button').forEach(button => {
            button.addEventListener('click', function() {
                const questionNumber = this.getAttribute('data-question');
                document.querySelector(`#square-link-${questionNumber}`).classList.add('reviewed');
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts_bottom'); ?>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/xs166855/4exam.online/public_html/resources/views/admin/quizzes/lists.blade.php ENDPATH**/ ?>