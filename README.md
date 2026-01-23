# Gym Management System / Hệ Thống Quản Lý Phòng Gym

## Overview / Tổng Quan

**English:**
A comprehensive Gym Management System built with pure PHP and SQLite. This application is designed to help gym owners manage members, subscription packages, and revenue efficiently. It features a clean, minimalist user interface and follows the MVC architectural pattern.

**Tiếng Việt:**
Một hệ thống quản lý phòng Gym toàn diện được xây dựng bằng PHP thuần và SQLite. Ứng dụng này được thiết kế để giúp chủ phòng gym quản lý hội viên, các gói tập và doanh thu một cách hiệu quả. Hệ thống sở hữu giao diện người dùng tối giản, hiện đại và tuân thủ mô hình kiến trúc MVC.

## Features / Tính Năng

### 1. Dashboard / Trang Chủ
- **English:** View real-time reports including total members, active subscriptions, and total revenue immediately after login.
- **Tiếng Việt:** Xem báo cáo thời gian thực bao gồm tổng số hội viên, các gói tập đang hoạt động và tổng doanh thu ngay sau khi đăng nhập.

### 2. Member Management / Quản Lý Hội Viên
- **English:** Add, edit, and view member details. Automatic status tracking ("Active" or "Expired") based on subscriptions. Quick search by name or phone number.
- **Tiếng Việt:** Thêm, sửa và xem chi tiết hội viên. Tự động theo dõi trạng thái ("CÒN HẠN" hoặc "HẾT HẠN") dựa trên các gói tập. Tìm kiếm nhanh theo tên hoặc số điện thoại.

### 3. Package Management / Quản Lý Gói Tập
- **English:** Create and manage diverse service packages (e.g., 1 Month, 3 Months, PT Sessions) with customizable prices and durations.
- **Tiếng Việt:** Tạo và quản lý đa dạng các gói dịch vụ (Ví dụ: 1 Tháng, 3 Tháng, Gói PT...) với giá tiền và thời hạn tùy chỉnh.

### 4. Subscription & Payments / Đăng Ký & Thu Tiền
- **English:** Streamlined workflow to register members for packages. The system automatically calculates expiration dates.
- **Tiếng Việt:** Quy trình đơn giản để đăng ký gói tập cho hội viên. Hệ thống tự động tính toán ngày hết hạn.

### 5. Role-based Access Control / Phân Quyền
- **English:**
    - **Super Admin:** Full access, including managing other admin accounts.
    - **Staff (Sub-Admin):** Access to all gym management features but cannot modify other admin accounts.
- **Tiếng Việt:**
    - **Admin Chính:** Toàn quyền hệ thống, bao gồm quản lý các tài khoản admin khác.
    - **Admin Phụ:** Sử dụng mọi tính năng quản lý phòng gym, ngoại trừ việc sửa/xóa các admin khác.

## License / Giấy Phép

Distributed under the MIT License.

---

### MIT License

Copyright (c) 2026

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
