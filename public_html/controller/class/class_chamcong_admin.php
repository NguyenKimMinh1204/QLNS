<?php
include '../../db/connetion.php';

class WiFi extends Database {
    public function loaddulieu() {
        $sql = 'SELECT id, ssid, ip_address, description FROM wifi_addresses ORDER BY id ASC';
        $link = $this->getConnection();

        // Check connection
        if ($link) {
            // Execute query
            $stmt = $link->prepare($sql);
            $stmt->execute();

            // Fetch all data
            $ketqua = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $i = count($ketqua);

            if ($i > 0) {
                echo '<table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 100px;">STT</th>
                                <th style="width: 250px;">Tên Wifi</th>
                                <th style="width: 150px;">Địa chỉ IP</th>
                                
                                <th style="width: 150px;">Hành động </th>
                            </tr>
                        </thead>
                        <tbody>';
                
                foreach ($ketqua as $row) {
                    $id = $row['id'];
                    $ssid = $row['ssid'];
                    $ip_address = $row['ip_address'];
                    $description = $row['description'];
                    
                    echo '                
                        <tr>
                            <td>'.$id.'</td>
                            <td>' .$ssid . '</td>
                            <td>' . $ip_address . '</td>
                           
                            <td>
                                <button class="btn btn-primary edit-btn" data-toggle="modal" data-target="#editModal" 
                                    data-id="' . $id . '" 
                                    data-ssid="' . $ssid . '" 
                                    data-ip="' . $ip_address. '" 
                                    data-description="' . $description . '">
                                    Edit
                                </button>
                                <button class="btn btn-danger delete-btn" data-id="' . htmlspecialchars($id) . '">Delete</button>
                            </td>
                        </tr>';
                }

                echo '</tbody>
                    </table>';
            } else {
                echo 'No data available';
            }
        } else {
            echo 'Unable to connect to the database';
        }
    }

    public function addWiFi($ssid, $ip_address, $description) {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare("INSERT INTO wifi_addresses (ssid, ip_address, description) VALUES (:ssid, :ip_address, :description)");
        $stmt->bindParam(':ssid', $ssid);
        $stmt->bindParam(':ip_address', $ip_address);
        $stmt->bindParam(':description', $description);
        
        return $stmt->execute(); // Returns true if successful, false if failed
    }

    public function updateWiFi($id, $ssid, $ip_address, $description) {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare("UPDATE wifi_addresses SET ssid = :ssid, ip_address = :ip_address, description = :description WHERE id = :id");
        $stmt->bindParam(':ssid', $ssid);
        $stmt->bindParam(':ip_address', $ip_address);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute() ? 1 : 0; // Returns 1 if successful, 0 if failed
    }

    public function deleteWiFi($id) {
        $dbh = $this->getConnection();
        $stmt = $dbh->prepare("DELETE FROM wifi_addresses WHERE id = :id LIMIT 1");
        $stmt->bindParam(':id', $id);
        
        return $stmt->execute() ? 1 : 0; // Returns 1 if successful, 0 if failed
    }
}

?>