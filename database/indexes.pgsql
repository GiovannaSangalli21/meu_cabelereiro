CREATE INDEX IF NOT EXISTS idx_agendamentos_data_horario
    ON agendamentos (data_agendamento, horario_inicio);