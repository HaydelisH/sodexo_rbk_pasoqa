USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_CargosEmpleado_eliminar2]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_CargosEmpleado_eliminar2]
	@pRutEmpresa VARCHAR(50),
	@pidCargoEmpleado VARCHAR(14)
AS
BEGIN
	SET NOCOUNT ON;
	
	DECLARE @lmensaje	VARCHAR(100)
	DECLARE @error		INT;
			
	BEGIN
        BEGIN TRANSACTION;
            DELETE FROM CargosEmpresa WHERE RutEmpresa = @pRutEmpresa AND idCargoEmpleado = @pidCargoEmpleado;
            DELETE FROM CargosEmpleado WHERE idCargoEmpleado = @pidCargoEmpleado;
        COMMIT TRANSACTION;
    END 
END
GO
