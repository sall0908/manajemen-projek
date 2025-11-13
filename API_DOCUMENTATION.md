# API Documentation untuk Flutter

## Base URL
```
http://localhost/api
```

## Authentication
Gunakan Sanctum token untuk request yang memerlukan autentikasi. Tambahkan header:
```
Authorization: Bearer {token}
```

---

## 🔐 Authentication Endpoints

### 1. Register
**POST** `/api/auth/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "role": "developer" // atau "designer", "teamleader"
}
```

**Response:**
```json
{
    "message": "User registered successfully",
    "user": { ... },
    "token": "token_string"
}
```

---

### 2. Login
**POST** `/api/auth/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "message": "Login successful",
    "user": { ... },
    "token": "token_string"
}
```

---

### 3. Get Profile
**GET** `/api/auth/profile`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "user": { ... }
}
```

---

### 4. Logout
**POST** `/api/auth/logout`

**Headers:**
```
Authorization: Bearer {token}
```

---

## 📁 Projects Endpoints

### 1. Get All Projects
**GET** `/api/projects`

**Headers:**
```
Authorization: Bearer {token}
```

**Response:**
```json
{
    "message": "Projects retrieved successfully",
    "data": [
        {
            "project_id": 1,
            "project_name": "Project A",
            "description": "Description",
            "status": "in_progress",
            "created_by": 1,
            "members": [ ... ],
            "cards": [ ... ]
        }
    ]
}
```

---

### 2. Get Project Detail
**GET** `/api/projects/{project_id}`

**Headers:**
```
Authorization: Bearer {token}
```

---

### 3. Create Project
**POST** `/api/projects`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "project_name": "New Project",
    "description": "Project description",
    "deadline": "2025-12-31",
    "difficulty": "medium" // easy, medium, hard
}
```

---

### 4. Get Project Members
**GET** `/api/projects/{project_id}/members`

**Headers:**
```
Authorization: Bearer {token}
```

---

## 📦 Cards Endpoints

### 1. Get All Cards by Project
**GET** `/api/cards/project/{project_id}`

**Headers:**
```
Authorization: Bearer {token}
```

---

### 2. Get Cards by Status
**GET** `/api/cards/project/{project_id}/status/{status}`

**Status options:** `todo`, `in_progress`, `review`, `done`

**Example:**
```
GET /api/cards/project/1/status/in_progress
```

---

### 3. Get Card Detail
**GET** `/api/cards/{card_id}`

**Headers:**
```
Authorization: Bearer {token}
```

---

### 4. Create Card
**POST** `/api/cards/project/{project_id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "card_title": "Card Title",
    "description": "Card description",
    "priority": "high", // low, medium, high
    "due_date": "2025-12-31",
    "estimated_hours": 8
}
```

---

### 5. Update Card Status
**PATCH** `/api/cards/{card_id}/status`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "status": "in_progress" // todo, in_progress, review, done
}
```

---

## 📝 SubTasks Endpoints

### 1. Get All SubTasks by Card
**GET** `/api/subtasks/card/{card_id}`

**Headers:**
```
Authorization: Bearer {token}
```

---

### 2. Get SubTasks by Status
**GET** `/api/subtasks/card/{card_id}/status/{status}`

**Status options:** `todo`, `in_progress`, `review`, `done`

---

### 3. Get SubTask Detail
**GET** `/api/subtasks/{sub_task_id}`

**Headers:**
```
Authorization: Bearer {token}
```

---

### 4. Create SubTask
**POST** `/api/subtasks/card/{card_id}`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "title": "SubTask Title",
    "description": "SubTask description"
}
```

---

### 5. Update SubTask Status
**PATCH** `/api/subtasks/{sub_task_id}/status`

**Headers:**
```
Authorization: Bearer {token}
Content-Type: application/json
```

**Request Body:**
```json
{
    "status": "done" // todo, in_progress, review, done
}
```

---

### 6. Toggle SubTask Completion
**PATCH** `/api/subtasks/{sub_task_id}/toggle`

**Headers:**
```
Authorization: Bearer {token}
```

---

## Error Responses

### Validation Error (422)
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "field_name": ["Error message"]
    }
}
```

### Unauthorized (401)
```json
{
    "message": "Unauthenticated."
}
```

### Forbidden (403)
```json
{
    "message": "Unauthorized access to this resource"
}
```

### Not Found (404)
```json
{
    "message": "Resource not found"
}
```

---

## Flutter Integration Example

```dart
import 'package:http/http.dart' as http;
import 'dart:convert';

class ApiService {
  final String baseUrl = 'http://localhost/api';
  String? token;

  // Login
  Future<void> login(String email, String password) async {
    final response = await http.post(
      Uri.parse('$baseUrl/auth/login'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode({
        'email': email,
        'password': password,
      }),
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      token = data['token'];
    } else {
      throw Exception('Login failed');
    }
  }

  // Get Projects
  Future<List> getProjects() async {
    final response = await http.get(
      Uri.parse('$baseUrl/projects'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      final data = jsonDecode(response.body);
      return data['data'];
    } else {
      throw Exception('Failed to get projects');
    }
  }

  // Update SubTask Status
  Future<void> updateSubTaskStatus(int subTaskId, String status) async {
    final response = await http.patch(
      Uri.parse('$baseUrl/subtasks/$subTaskId/status'),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
      },
      body: jsonEncode({
        'status': status,
      }),
    );

    if (response.statusCode != 200) {
      throw Exception('Failed to update subtask status');
    }
  }
}
```
