USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_checkListDocumentos_eliminar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_checkListDocumentos_eliminar]
	@idTipoMovimiento INT,
	@idTipoGestor INT
AS
BEGIN
	SET NOCOUNT ON;
	DECLARE @error		INTEGER;
	DECLARE @mensaje	VARCHAR(100);	
    SET @mensaje = '';
    SET @error = 0;
    
	BEGIN
        BEGIN TRANSACTION;
            DELETE FROM ChecklistDocumentos WHERE  idTipoMovimiento = @idTipoMovimiento AND idTipoGestor = @idTipoGestor;
        COMMIT TRANSACTION;
        SELECT @error AS error, @mensaje AS mensaje;
    END;
END;
GO
