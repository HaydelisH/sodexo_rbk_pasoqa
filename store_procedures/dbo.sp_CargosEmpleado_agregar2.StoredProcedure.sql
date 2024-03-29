USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_CargosEmpleado_agregar2]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_CargosEmpleado_agregar2]
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
			
	IF NOT EXISTS ( SELECT RutEmpresa FROM CargosEmpresa WHERE RutEmpresa = @pRutEmpresa AND idCargoEmpleado = @pidCargoEmpleado )
		BEGIN
            BEGIN TRANSACTION;
			    INSERT INTO CargosEmpleado (idCargoEmpleado, Descripcion, Eliminado, Titulo,Obligaciones )
			        VALUES (@pidCargoEmpleado, @pDescripcion, 0, @pTitulo, @pObligaciones);
                INSERT INTO CargosEmpresa (RutEmpresa, idCargoEmpleado)
                    VALUES (@pRutEmpresa, @pidCargoEmpleado);
            COMMIT TRANSACTION;
		END 
	ELSE
		BEGIN 
            SET @error = 1;
            SET @lmensaje = 'El codigo de cargo ya existe para la empresa.';
			SELECT @error AS error, @lmensaje AS mensaje;
		END
END
GO
