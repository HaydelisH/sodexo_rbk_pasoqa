USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_CargosEmpleado_modificar2]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_CargosEmpleado_modificar2]
	@pRutEmpresa VARCHAR(50),
	@pidCargoEmpleado VARCHAR(14),
	@pTitulo VARCHAR(200),
	@pDescripcion VARCHAR(200),
	@pObligaciones VARCHAR(MAX)
AS
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
			
    BEGIN
        BEGIN TRANSACTION;
            UPDATE CargosEmpleado SET 
                Titulo = @pTitulo,
                Descripcion = @pDescripcion,
                Obligaciones = @pObligaciones
            WHERE 
                idCargoEmpleado = @pidCargoEmpleado;
        COMMIT TRANSACTION;
    END 
END
GO
