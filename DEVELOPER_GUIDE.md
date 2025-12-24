# Developer Guide - Sistem Presensi RFID

## 🚀 Quick Start

### Prerequisites
- PHP 7.2+
- MySQL 5.7+
- Apache/Nginx with mod_rewrite
- Composer (optional, for libraries)

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/apriansasyadrik/presensi-rfid.git
   cd presensi-rfid
   ```

2. **Run setup script** (Linux/Mac)
   ```bash
   ./setup.sh
   ```
   
   Or manually:
   ```bash
   # Set permissions
   chmod -R 777 application/cache application/logs uploads
   
   # Create database and import
   mysql -u root -p
   CREATE DATABASE presensi_rfid;
   exit
   
   mysql -u root -p presensi_rfid < database_schema.sql
   ```

3. **Configure database**
   Edit `application/config/database.php`:
   ```php
   'username' => 'your_username',
   'password' => 'your_password',
   'database' => 'presensi_rfid',
   ```

4. **Access application**
   - Main: `http://localhost/presensi-rfid`
   - RFID Scanner: `http://localhost/presensi-rfid/rfid-scanner`
   - Login: admin / admin123

## 📁 Project Structure

```
presensi-rfid/
├── application/
│   ├── controllers/
│   │   ├── Auth.php                    # Authentication
│   │   ├── Rfid_scanner.php            # Public RFID scanner
│   │   ├── admin/                      # Admin controllers
│   │   │   ├── Dashboard.php
│   │   │   ├── Pengaturan_sekolah.php
│   │   │   ├── Tahun_ajaran.php
│   │   │   └── ...
│   │   ├── guru/                       # Teacher controllers
│   │   └── bk/                         # Counselor controllers
│   ├── models/
│   │   ├── Auth_model.php
│   │   ├── Rfid_model.php
│   │   └── admin/                      # Admin models
│   ├── views/
│   │   ├── auth/                       # Auth views
│   │   │   └── login.php
│   │   ├── admin/                      # Admin views
│   │   │   ├── templates/              # Templates
│   │   │   │   ├── header.php
│   │   │   │   ├── sidebar.php
│   │   │   │   └── footer.php
│   │   │   └── dashboard.php
│   │   ├── guru/                       # Teacher views
│   │   ├── bk/                         # Counselor views
│   │   └── public/                     # Public views
│   │       └── rfid_scanner.php
│   ├── core/
│   │   └── MY_Controller.php           # Base controller with auth
│   └── config/
│       ├── config.php                  # Main config
│       ├── database.php                # DB config
│       ├── autoload.php                # Autoload config
│       └── routes.php                  # URL routes
├── uploads/                            # Upload directory
├── database_schema.sql                 # Database schema
├── setup.sh                            # Setup script
├── README.md                           # Main documentation
└── IMPLEMENTATION_STATUS.md            # Implementation status
```

## 🏗️ Architecture Patterns

### 1. MVC Pattern
```php
// Controller (application/controllers/admin/Example.php)
class Example extends MY_Controller {
    protected $allowed_roles = array('admin');
    
    public function index() {
        $data['items'] = $this->Example_model->get_all();
        $this->load_template('admin/example', $data);
    }
}

// Model (application/models/admin/Example_model.php)
class Example_model extends CI_Model {
    public function get_all() {
        return $this->db->get('table_name')->result();
    }
}

// View (application/views/admin/example.php)
// HTML content here, using $items variable
```

### 2. Authentication Pattern
```php
// Protect controller with role-based access
class MyController extends MY_Controller {
    protected $allowed_roles = array('admin', 'guru');
    // Only admin and guru can access
}
```

### 3. Template Loading
```php
// Load view with template
$data['title'] = 'Page Title';
$this->load_template('admin/view_name', $data);
// This loads: header, sidebar, view, footer
```

## 🎨 Creating New CRUD Pages

### Step 1: Create Controller
```php
// application/controllers/admin/MyFeature.php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyFeature extends MY_Controller {
    protected $allowed_roles = array('admin');
    
    public function __construct() {
        parent::__construct();
        $this->load->model('admin/MyFeature_model');
    }
    
    public function index() {
        $data['title'] = 'My Feature';
        $data['items'] = $this->MyFeature_model->get_all();
        $this->load_template('admin/my_feature', $data);
    }
    
    public function add() {
        // AJAX endpoint for adding
        if (!$this->input->is_ajax_request()) {
            show_404();
            return;
        }
        
        // Validation
        $this->form_validation->set_rules('field', 'Field', 'required');
        
        if ($this->form_validation->run() == FALSE) {
            echo json_encode(['success' => false, 'message' => validation_errors()]);
            return;
        }
        
        // Insert
        $data = ['field' => $this->input->post('field')];
        if ($this->MyFeature_model->insert($data)) {
            echo json_encode(['success' => true, 'message' => 'Success']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed']);
        }
    }
    
    // Similar for edit, delete, get methods
}
```

### Step 2: Create Model
```php
// application/models/admin/MyFeature_model.php
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MyFeature_model extends CI_Model {
    protected $table = 'table_name';
    
    public function get_all() {
        return $this->db->get($this->table)->result();
    }
    
    public function get_by_id($id) {
        $this->db->where('id', $id);
        return $this->db->get($this->table)->row();
    }
    
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }
    
    public function update($id, $data) {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
```

### Step 3: Create View
```php
// application/views/admin/my_feature.php
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-2xl font-bold">My Feature</h3>
        <button onclick="openAddModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            <i class="fas fa-plus mr-2"></i> Add New
        </button>
    </div>
    
    <table class="min-w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left py-2 px-4">Column</th>
                <th class="text-center py-2 px-4">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $item): ?>
            <tr class="border-b hover:bg-gray-50">
                <td class="py-3 px-4"><?= $item->field ?></td>
                <td class="py-3 px-4 text-center">
                    <button onclick="editItem(<?= $item->id ?>)" class="text-blue-600 hover:text-blue-800">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="deleteItem(<?= $item->id ?>)" class="text-red-600 hover:text-red-800 ml-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal for Add/Edit -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 id="modalTitle" class="text-xl font-bold mb-4">Add Item</h3>
        <form id="itemForm">
            <input type="hidden" id="item_id">
            <div class="mb-4">
                <label class="block text-sm font-medium mb-2">Field</label>
                <input type="text" id="field" class="w-full px-3 py-2 border rounded-lg">
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add Item';
    document.getElementById('itemForm').reset();
    document.getElementById('item_id').value = '';
    document.getElementById('modal').classList.remove('hidden');
}

function editItem(id) {
    // Fetch item data via AJAX
    fetch('<?= base_url("admin/my-feature/get/") ?>' + id)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalTitle').textContent = 'Edit Item';
                document.getElementById('item_id').value = data.data.id;
                document.getElementById('field').value = data.data.field;
                document.getElementById('modal').classList.remove('hidden');
            }
        });
}

function closeModal() {
    document.getElementById('modal').classList.add('hidden');
}

document.getElementById('itemForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const id = document.getElementById('item_id').value;
    const url = id ? '<?= base_url("admin/my-feature/edit/") ?>' + id : '<?= base_url("admin/my-feature/add") ?>';
    const formData = new FormData(this);
    
    fetch(url, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.message);
        }
    });
});

function deleteItem(id) {
    if (confirm('Are you sure?')) {
        fetch('<?= base_url("admin/my-feature/delete/") ?>' + id, {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert(data.message);
            }
        });
    }
}
</script>
```

## 🔌 Adding External Libraries

### PhpSpreadsheet (Excel)
```bash
composer require phpoffice/phpspreadsheet
```

Usage in controller:
```php
require_once FCPATH . 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// Export Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World!');

$writer = new Xlsx($spreadsheet);
$filename = 'export_' . date('YmdHis') . '.xlsx';
$writer->save('uploads/excel/' . $filename);
```

### DOMPDF (PDF)
```bash
composer require dompdf/dompdf
```

Usage in controller:
```php
require_once FCPATH . 'vendor/autoload.php';
use Dompdf\Dompdf;

$dompdf = new Dompdf();
$html = $this->load->view('pdf/template', $data, true);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream('document.pdf');
```

## 🎯 Best Practices

1. **Always validate input**
   ```php
   $this->form_validation->set_rules('field', 'Field', 'required|trim|xss_clean');
   ```

2. **Use transactions for critical operations**
   ```php
   $this->db->trans_start();
   // operations
   $this->db->trans_complete();
   ```

3. **Sanitize output**
   ```php
   <?= htmlspecialchars($data) ?>
   ```

4. **Use flash messages**
   ```php
   $this->session->set_flashdata('success', 'Message');
   ```

5. **Handle AJAX properly**
   ```php
   if (!$this->input->is_ajax_request()) {
       show_404();
       return;
   }
   ```

## 🐛 Common Issues

### Issue: 404 on all pages except homepage
**Solution**: Enable mod_rewrite and check .htaccess

### Issue: Session not working
**Solution**: Check cache directory permissions: `chmod 777 application/cache`

### Issue: Database connection failed
**Solution**: Verify credentials in `application/config/database.php`

### Issue: Upload failed
**Solution**: Check upload directory permissions: `chmod 777 uploads`

## 📚 Resources

- [CodeIgniter 3 Documentation](https://codeigniter.com/userguide3/)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Font Awesome Icons](https://fontawesome.com/icons)
- [PhpSpreadsheet Documentation](https://phpspreadsheet.readthedocs.io/)
- [DOMPDF Documentation](https://github.com/dompdf/dompdf)

## 🤝 Contributing

1. Follow existing code structure
2. Use proper naming conventions
3. Add comments for complex logic
4. Test before committing
5. Update documentation

## 📞 Support

For issues or questions:
- Check IMPLEMENTATION_STATUS.md for feature status
- Review this guide for patterns
- Check existing code for examples

---

**Happy Coding! 🚀**
