-- sample users (admin, staff, offices, patients)
INSERT INTO dt_users (name, email, password_hash, role) VALUES
('System Admin', 'admin@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'admin'),
('Front Desk Staff', 'staff@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'staff'),
('Downtown Care Office', 'office1@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'office'),
('Lakeside Health Office', 'office2@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'office'),
('Uptown Wellness Office', 'office3@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'office'),
('Suburban Family Office', 'office4@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'office'),
('Harbor Medical Office', 'office5@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'office'),
('Patient One', 'patient1@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'patient'),
('Patient Two', 'patient2@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'patient'),
('Patient Three', 'patient3@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'patient'),
('Patient Four', 'patient4@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'patient'),
('Patient Five', 'patient5@example.com', '$2y$10$QtNItVWUjrXVgf5T0OTbA.q0Xi3.vGQLqdHPq7ZxNz8PboQtHmJqW', 'patient');

-- sample specialties
INSERT INTO dt_specialties (name, description) VALUES
('Cardiology', 'Heart and vascular care'),
('Pediatrics', 'Child health and wellness'),
('Dermatology', 'Skin, hair, and nail care'),
('Orthopedics', 'Bones, joints, and muscles'),
('General Practice', 'Primary care and family medicine');

-- sample offices linked to office users (user_id 3-7)
INSERT INTO dt_offices (user_id, office_name, address, phone, website, status, description) VALUES
(3, 'Downtown Care Medical Center', '123 Main St, Suite 200', '555-0101', 'https://downtowncare.example.com', 'approved', 'Multidisciplinary clinic downtown.'),
(4, 'Lakeside Health Collective', '456 Lakeview Blvd', '555-0102', 'https://lakesidehealth.example.com', 'approved', 'Integrative care near the lake.'),
(5, 'Uptown Wellness Hub', '789 Uptown Ave', '555-0103', NULL, 'approved', 'Wellness and diagnostic services.'),
(6, 'Suburban Family Practice', '234 Maple St', '555-0104', NULL, 'approved', 'Family medicine with evening hours.'),
(7, 'Harbor Medical Group', '567 Harbor Rd', '555-0105', 'https://harbormedical.example.com', 'approved', 'Coordinated care for coastal residents.');

-- sample doctors
INSERT INTO dt_doctors (office_id, name, specialty_id, bio) VALUES
(1, 'Dr. Clara Hayes', 1, 'Board-certified cardiologist with 12 years experience.'),
(1, 'Dr. Marcus Lee', 5, 'Family physician focused on preventive care.'),
(2, 'Dr. Priya Shah', 2, 'Pediatrician with a background in neonatology.'),
(2, 'Dr. René Ortiz', 3, 'Dermatologist specializing in psoriasis.'),
(3, 'Dr. Amina Taylor', 5, 'General practitioner with broad internal medicine skills.'),
(3, 'Dr. Julius Park', 4, 'Orthopedic surgeon, joint reconstruction lead.'),
(4, 'Dr. Helena Costa', 1, 'Cardiology specialist for women\'s heart health.'),
(4, 'Dr. Elias Green', 4, 'Sports medicine and orthopedics.'),
(5, 'Dr. Zoe Bennett', 3, 'Clinical dermatologist focusing on acne treatment.'),
(5, 'Dr. Noah Wallace', 2, 'Pediatric care with child development focus.');

-- sample appointments across doctors, patients, and offices
INSERT INTO dt_appointments (patient_id, doctor_id, office_id, appointment_datetime, status) VALUES
(8, 1, 1, '2025-12-03 09:00:00', 'confirmed'),
(9, 2, 1, '2025-12-03 11:30:00', 'pending'),
(10, 3, 2, '2025-12-04 10:00:00', 'confirmed'),
(11, 4, 2, '2025-12-04 14:00:00', 'cancelled'),
(12, 5, 3, '2025-12-05 09:30:00', 'confirmed'),
(8, 6, 3, '2025-12-06 13:00:00', 'completed'),
(9, 7, 4, '2025-12-07 10:45:00', 'pending'),
(10, 8, 4, '2025-12-07 15:00:00', 'confirmed'),
(11, 9, 5, '2025-12-08 09:00:00', 'completed'),
(12, 10, 5, '2025-12-08 13:30:00', 'pending'),
(8, 1, 1, '2025-12-09 10:15:00', 'confirmed'),
(9, 2, 1, '2025-12-09 14:00:00', 'cancelled'),
(10, 3, 2, '2025-12-10 11:00:00', 'confirmed'),
(11, 4, 2, '2025-12-10 16:30:00', 'pending'),
(12, 5, 3, '2025-12-11 08:45:00', 'confirmed'),
(8, 6, 3, '2025-12-11 12:30:00', 'pending'),
(9, 7, 4, '2025-12-12 09:15:00', 'completed'),
(10, 8, 4, '2025-12-12 14:45:00', 'pending'),
(11, 9, 5, '2025-12-13 10:00:00', 'confirmed'),
(12, 10, 5, '2025-12-13 15:15:00', 'completed');

-- dt_staff_actions log of administrative events
INSERT INTO dt_staff_actions (staff_id, action_type, target_type, target_id, description) VALUES
(2, 'approve_office', 'office', 3, 'Approved Uptown Wellness Hub for patient bookings.'),
(2, 'edit_office', 'office', 4, 'Updated hours and contact info for Suburban Family Practice.'),
(2, 'ban_user', 'user', 12, 'Temporarily disabled patient account for suspicious activity.'),
(2, 'resolve_report', 'appointment', 5, 'Dismissed duplicate booking report for appointment #5.');

-- sample office slots (blocked slots for doctors)
-- Doctor 1 (Clara Hayes) at Office 1
INSERT INTO dt_office_slots (office_id, doctor_id, slot_datetime, status, note) VALUES
(1, 1, '2025-12-03 09:30:00', 'blocked', 'Leave'),
(1, 1, '2025-12-04 14:30:00', 'blocked', 'Training session'),
(1, 1, '2025-12-10 10:00:00', 'blocked', 'Conference attendance'),

-- Doctor 2 (Marcus Lee) at Office 1
(1, 2, '2025-12-03 10:00:00', 'available', NULL),
(1, 2, '2025-12-03 14:00:00', 'blocked', 'Staff meeting'),
(1, 2, '2025-12-05 11:00:00', 'available', NULL),

-- Doctor 3 (Priya Shah) at Office 2
(2, 3, '2025-12-04 10:30:00', 'available', NULL),
(2, 3, '2025-12-04 15:00:00', 'blocked', 'Emergency call'),
(2, 3, '2025-12-06 09:00:00', 'available', NULL),

-- Doctor 4 (René Ortiz) at Office 2
(2, 4, '2025-12-04 14:30:00', 'available', NULL),
(2, 4, '2025-12-05 13:00:00', 'blocked', 'Lunch break extended'),
(2, 4, '2025-12-07 11:00:00', 'available', NULL),

-- Doctor 5 (Amina Taylor) at Office 3
(3, 5, '2025-12-05 09:00:00', 'available', NULL),
(3, 5, '2025-12-05 10:00:00', 'blocked', 'Patient follow-up'),
(3, 5, '2025-12-06 14:00:00', 'available', NULL),

-- Doctor 6 (Julius Park) at Office 3
(3, 6, '2025-12-06 13:30:00', 'available', NULL),
(3, 6, '2025-12-07 10:00:00', 'blocked', 'Surgery prep'),
(3, 6, '2025-12-08 09:00:00', 'available', NULL),

-- Doctor 7 (Helena Costa) at Office 4
(4, 7, '2025-12-07 10:00:00', 'available', NULL),
(4, 7, '2025-12-07 11:30:00', 'blocked', 'Research time'),
(4, 7, '2025-12-09 13:00:00', 'available', NULL),

-- Doctor 8 (Elias Green) at Office 4
(4, 8, '2025-12-07 15:30:00', 'available', NULL),
(4, 8, '2025-12-08 10:00:00', 'blocked', 'Journal club'),
(4, 8, '2025-12-09 14:00:00', 'available', NULL),

-- Doctor 9 (Zoe Bennett) at Office 5
(5, 9, '2025-12-08 09:30:00', 'available', NULL),
(5, 9, '2025-12-08 15:00:00', 'blocked', 'Lab work'),
(5, 9, '2025-12-10 10:00:00', 'available', NULL),

-- Doctor 10 (Noah Wallace) at Office 5
(5, 10, '2025-12-08 13:00:00', 'available', NULL),
(5, 10, '2025-12-08 14:00:00', 'blocked', 'Parent consultation'),
(5, 10, '2025-12-12 09:00:00', 'available', NULL);
