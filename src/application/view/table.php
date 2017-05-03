    <div class="col-sm-9">
          <h2>View 클래스 샘플 테이블</h2>
          <p>해당 페이지는 뷰의 샘플 페이지입니다.</p>
          <table class="table">
            <thead>
              <tr>
                <th>유저 번호</th>
                <th>유저 이름</th>
                <th>유저 별명</th>
                <th>유저 회원번호</th>
              </tr>
            </thead>
            <tbody>
              <?php
                foreach ($user as $key => $value) {
                    echo "<tr>";
                    echo "<td>{$value->user_index}</td>";
                    echo "<td>{$value->user_name}</td>";
                    echo "<td>{$value->user_alias}</td>";
                    echo "<td>{$value->user_password}</td>";
                    echo "</tr>";
                };
              ?>
            </tbody>
          </table>
    </div>

