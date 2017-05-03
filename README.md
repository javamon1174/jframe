
# JFramework by Javamon

제이프레임워크는 PHP COMPOSER 기반 프레임 워크입니다.
MVC패턴을 적용하고 앞으로의 유연함과 확장성을 고려하여 구현하였습니다.

### 개발환경
|서버| 운영체제|프로젝트 설명|개발기간|개발인원|작업환경|개발환경|
| ------------- |:-------------:| -----:|-----:|-----:|-----:|-----:|
|AWS|Ubuntu 16.04|MVC패턴 기반의 심플 프레임워크|17.5.1~3|단독|MAC OS|PHP 7.0.*  APACHE2  MariaDB 10.*|

### 동기
개인 프로젝트를 진행하면서 공통된 작업들을 반복적으로 하는데에 많은 시간이 소요되면서 이에 따른 "틀"의 필요성을 느꼈으며, CI와 라라벨을 사용하면서 느꼈던 필수적인 부분에 대한 집합을 고려하여 해당 프로젝트를 시작하게 되었습니다.

### 제이프레임워크 관해
제이 프레임워크는 굉작히 작습니다. 단순히, http 요청을 받고 그에 따른 MVC형태만 가지고 있지만, 추후에 리팩토링을 통해 "가장 필요함"에 중점을 두고 확장시킬 예정입니다.
또한, 작은 만큼 더욱 더 다양한 형태로 확장이 가능하다는 부분도 장점이라고 생각합니다.

#### 제이프레임워크는..
- 디렉터리의 구조는 어플리케이션의 하위에 코어가 있는 구조인 라라벨과 유사합니다.
- 라우트는 매우 심플하며, 아파치의 rewrite와 GET요청만을 활용하여 구성되었습니다.
- 라라벨의 엘로퀸드 ORM 방식과 유사한 ORM을 가지고 있습니다.
- 누구든지 쉽게, 또는 바로 코어 클래스들을 수정/보완하여 커스터마이징이 가능합니다.
- 내장된 함수를 통해, 몇줄 안되는 소스로 데이터베이스 테이블과의 입출력이 가능합니다.

#### 제이프레임워크의 발전방향
- HTTP의 요청(POST, GET 등) 처리를 위한 클래스.
- 쿠키, 세션 등을 위한, 활용하기 쉬운 클래스.
- 백그라운드 프로세싱
- 데이터베이스 스키마 마이그레이션
- 라라벨의 artisan과 같은 스크립트 기반의 프로세스
- 다양한 공격(인젝션 등)에 대응할 수 있는 보안.

### 제이프레임워크 사용법
URL ROUTE는 CodeIgniter와 동일합니다. 세그먼트로, http://127.0.0.1/class/function/data 입니다.
##### 샘플 컨트롤러(프로세서) 기본 구조
```
    namespace Javamon\Jframe\Processor;
    use \Javamon\Jframe\Core\Processor as Processor;
    class Sample extends Processor {
    public function Sample($arg) {}
    }
```

##### 모델(쿼리빌더) 사용 예제
```
    $this->model->select(테이블, 조회조건, 조건 필드, 조건 값);
    $this->model->select('user', 'user_name','user_index', 6);
    $this->model->update(테이블, 수정 필드, 수정 값, 조건필드, 조건 값);
    $this->model->update('user', 'user_name','power','user_index', 5);
    $this->model->delete(테이블, 삭제조건 필드, 삭제 조건값);
    $this->model->delete('user', 'user_index', 5);
```

##### 엘로퀸트 ORM 사용 예제
```
    User::ORM()->select(조회조건, 조건 필드, 조건 값);
    User::ORM()->update(수정 필드,수정 값, 조건 필드, 조건 값);
    User::ORM()->delete(삭제조건 필드, 삭제 조건값);
```

##### 뷰 사용 예제
```
    $this->view->load(뷰 페이지(배열), 뷰 전달 데이터(배열));
    $layout[] = "header";
    $layout[] = "table";
    $layout[] = "footer";
    $this->view->load($layout, $data);
```

### 공헌
해당 깃헙에 풀 리퀘스트 요청.
