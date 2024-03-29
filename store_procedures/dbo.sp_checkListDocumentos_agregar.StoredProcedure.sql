USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_checkListDocumentos_agregar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_checkListDocumentos_agregar]
	@idTipoMovimiento INT,
	@idTipoGestor INT,
	@Obligatorio BIT
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @error		INTEGER;
	DECLARE @mensaje	VARCHAR(100);	
    SET @mensaje = '';
    SET @error = 0;
    
	BEGIN
        IF NOT EXISTS (SELECT 1 FROM ChecklistDocumentos WHERE idTipoMovimiento = @idTipoMovimiento AND idTipoGestor = @idTipoGestor)
        BEGIN
            BEGIN TRANSACTION;
                INSERT INTO ChecklistDocumentos VALUES (@idTipoMovimiento, @idTipoGestor, @Obligatorio);
            COMMIT TRANSACTION;
        END;
        ELSE
        BEGIN
            SELECT @mensaje = 'El tipo de gestor ya existe para el tipo de movimiento.';
            SELECT @error = 1;
        END;
        SELECT @error AS error, @mensaje AS mensaje;
    END;
END;
GO
