=== Woocommerce Vietnam Checkout ===
Contributors: levantoan
Donate link: https://levantoan.com/donate/
Tags: woocommerce, woo viet, woocommerce vietnam checkout, quan huyen, vietnam checkout
Requires at least: 4.3
Tested up to: 6.4.2
Stable tag: 2.0.9
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Woocommerce Vietnam Checkout - Thêm tỉnh/thành phố, quận huyện, xã/phường/thị trấn vào form checkout của woocommerce và tối giản form checkout cho phù hợp với Việt Nam

== Description ==

Plugin này dành riêng cho các website sử dụng Woocommerce tại Việt Nam và bán hàng tại khu vực Việt Nam.

Chức năng chính là Thêm tỉnh/thành phố, quận huyện, xã/phường/thị trấn vào form checkout của woocommerce và tối giản form checkout cho phù hợp với Việt Nam

Danh sách tính năng hiện có:

- Thêm field số điện thoại bên shipping
- Có thể tính giá shipping theo tỉnh thành phố
- Thêm số điện thoại người nhận và hiển thị lên hóa đơn
- Chuyển First name & Last name thành Họ và tên
- Thêm mục chọn tỉnh/thành phố
- Thêm mục chọn Quận/huyện
- Thêm mục chọn xã/phường/thị trấn
- Chuyển mục Địa chỉ xuống cuối cùng
- Không thêm sql – tất cả dữ liệu tỉnh thành ở dạng array
- Ẩn 1 số filed không cần thiết
- Chuyển ₫ sang VNĐ
- Loại bỏ tiêu đề của các phương thức vận chuyển
- Hỗ trợ thanh toán qua Paypal (Có tùy chỉnh tỷ giá quy đổi từ VNĐ sang các tiền tệ hỗ trợ Paypal)

[Phiên bản PRO](http://levantoan.com/plugin-tinh-phi-van-chuyen-cho-quan-huyen-trong-woocommerce/)

- Cho phép tính phí vận chuyển tới cấp tỉnh/thành phố; quận/huyện và xã/phường/thị trấn
- Cho phép tính phí vận chuyển theo tổng giá trị đơn hàng tới quận/huyện. Ví dụ tại Hà Nội bình thường là 20k ship và đơn hàng >500k thì free ship
- Cho phép tính phí vận chuyển theo cân nặng tới Quận/Huyện; Xã/Phường/Thị trấn.

### Cách sử dụng

Plugin này chỉ cần active là đã áp dụng vào trang thanh toán. Mà bạn không cần cài đặt gì thêm.
Ngoài ra có mục cài đặt thêm nhiều tính năng khác tại mục cài đặt của plugin.

Các bạn có thể tham khảo thêm PRO Version - Tính phí vận chuyển tới quận huyện [tại đây](http://levantoan.com/plugin-tinh-phi-van-chuyen-cho-quan-huyen-trong-woocommerce/)

https://www.youtube.com/watch?v=SQ4hQNE9TpM

== Installation ==

Các bước cài đặt thủ công:

1. Trước tiên tải plugin này về và có thư mục tên `woo-vietnam-checkout`. Hãy tải nó lên `/wp-content/plugins/`.
2. Sau đó vào `Quản lý plugins` và active plugin `Woocommerce Vietnam Checkout`.
3. Cơ bản đã xong. Bạn có thể vào `Settings / Vietnam Checkout` trong menu của admin để chọn thêm nhiều tùy chọn khác nhau.

Để có được các tính năng tính phí vận chuyển tới quận/huyện bạn hãy tham khảo bản PRO - [Pro version](http://levantoan.com/plugin-tinh-phi-van-chuyen-cho-quan-huyen-trong-woocommerce/) Trong phiên bản Pro bạn có thể tính phí vận chuyển theo cân nặng hoặc tổng giá trị đơn hàng tới từng Quận/huyện.

== Screenshots ==

1. Giao diện Form Checkout.
2. Giao diện toàn bộ trang checkout.
3. Phần cài đặt của plugin.
4. Thông tin được hiển thị trong quản trị đơn hàng.
5. Thông tin sau khi đặt hàng.

== Frequently Asked Questions ==

= Bạn thêm trang checkout mới. Không phải là trang checkout mặc định. Và bị lỗi không load được Tỉnh/thành? =

Hãy thêm code này vào functions.php của theme bạn đang sử dụng là được nhé
`add_filter('vn_checkout_allow_script_all_page', '__return_true');`

= Làm sao để ô Email không bắt buộc =

Hãy thêm code này vào functions.php của theme bạn đang sử dụng là được nhé
`add_filter('woocommerce_checkout_fields', 'devvn_custom_email_required', 999999);
 function devvn_custom_email_required($fields){
     $fields['billing']['billing_email']['required'] = false;
     return $fields;
 }`


== Changelog ==

Thông tin thêm [về plugin này](https://levantoan.com/lua-chon-tinhthanh-pho-vao-form-checkout-cua-woocommerce/).
Xem thêm bản PRO [tại đây](https://levantoan.com/plugin-tinh-phi-van-chuyen-cho-quan-huyen-trong-woocommerce/).

= 2.0.9 =

* Update bản dịch

= 2.0.8 =

* Fix XSS security. Thanks for Darius Sveikauskas (Patchstack Alliance)

= 2.0.7 =

* Update core

= 2.0.6 =

* Thêm filter vn_checkout_allow_script_all_page để gọi js và css của vn checkout ở mọi page. Mục đích để tương thích với 1 số plugin như cartFlows
* Tương thích với HPOS trong Woocommerce bản mới nhất
* Tối ưu lại 1 số hàm

= 2.0.5 =

* Fix XSS security. Thanks for MINKYU (Patchstack Alliance)

= 2.0.4 =

* Update tối ưu lại hàm check file get-address.php để tránh gây tốn tài nguyên hosting/vps

= 2.0.3 =

* Bỏ nén js tránh lỗi ở bản trước
* Bỏ thông báo nhập license

= 2.0.2 =

* Update danh sách địa giới hành chính mới nhất ngày 27/04/2021
* Fix 1 số lỗi nhỏ

= 2.0.1 =

* Update danh sách địa giới hành chính mới nhất ngày 15/01/2021
* Fix 1 số css nhỏ

= 2.0.0 =

* Tương thích với WordPress 5.6 và Woocommerce 4.8.0
* Tối ưu lại địa chỉ quận huyện và tỉnh thành
* Nâng cấp tính năng load địa chỉ hành chính nhanh hơn nhiều lần so với bản cũ

= 1.0.7 =

* Fix: Sửa lỗi với woocommerce 3.7.0

= 1.0.6 =

* Update: Change .live to .on in jquery

= 1.0.5 =

* Fix: Chỉnh lại css cho tương thích với Nitro theme

= 1.0.4 =

* Fix: chỉnh lỗi với theme Flatsome

= 1.0.3 =

* Update: Support cổng thanh toán Alepay (Setting -> Vietnam checkout -> Kích hoạt Alepay)

= 1.0.2 =

* Update: 99% Tương thích với plugin "WooCommerce Checkout Field Editor (Manager) Pro"

= 1.0.1 =

* Add: Hỗ trợ thanh toán qua Paypal (Tham khảo plugin "Woo Viet" của anh htdat)
* Update: Có thể chỉnh sửa địa chỉ tỉnh thành; quận/huyện khi sửa đơn hàng trong admin

= 1.0 =

* Update new plugin