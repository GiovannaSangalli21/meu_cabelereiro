ALTER TABLE agendamentos
    ADD COLUMN IF NOT EXISTS horario_inicio TIME;

ALTER TABLE agendamentos
    ADD COLUMN IF NOT EXISTS email_cliente VARCHAR(100);

DO $$
BEGIN
    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = current_schema()
          AND table_name = 'agendamentos'
          AND column_name = 'horario'
    ) THEN
        EXECUTE 'UPDATE agendamentos SET horario_inicio = horario WHERE horario_inicio IS NULL';
        EXECUTE 'ALTER TABLE agendamentos ALTER COLUMN horario DROP NOT NULL';
    END IF;

    IF EXISTS (
        SELECT 1
        FROM information_schema.columns
        WHERE table_schema = current_schema()
          AND table_name = 'agendamentos'
          AND column_name = 'cabeleireira_id'
    ) THEN
        EXECUTE 'ALTER TABLE agendamentos ALTER COLUMN cabeleireira_id DROP NOT NULL';
    END IF;
END;
$$;
