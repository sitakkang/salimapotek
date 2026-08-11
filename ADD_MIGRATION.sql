/* 11-08-2026 */
ALTER TABLE sks
ADD sks_nik VARCHAR(50) NULL;

ALTER TABLE trans_skbs
ADD skbs_blood_press VARCHAR(50) NULL;
ALTER TABLE trans_skbs
ADD skbs_pulse VARCHAR(50) NULL;
ALTER TABLE trans_skbs
ADD skbs_respirasi VARCHAR(50) NULL;
ALTER TABLE trans_skbs
ADD skbs_temp VARCHAR(50) NULL;

ALTER TABLE trans_skbs
ADD skbs_docnumb VARCHAR(100) NULL;

ALTER TABLE trans_skbs
ADD skbs_address VARCHAR(255) NULL;

ALTER TABLE trans_skbs
ADD skbs_birth_place VARCHAR(255) NULL;

ALTER TABLE trans_skbs
ADD skbs_bod date NULL;

ALTER TABLE trans_skbs
ADD skbs_gender VARCHAR(10) NULL;


ALTER TABLE ms_patient
ADD patient_birth_place VARCHAR(225) NULL;

