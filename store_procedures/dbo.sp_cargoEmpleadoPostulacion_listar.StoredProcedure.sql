USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_cargoEmpleadoPostulacion_listar]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_cargoEmpleadoPostulacion_listar]
	@RutEmpresa CHARACTER(10)
AS
BEGIN
	SET NOCOUNT ON;
	BEGIN
        SELECT 
            CargosEmpleado.idCargoEmpleado,
            CargosEmpleado.Descripcion  AS nombrecargoempleado,
            CargosEmpresa.fechaCaducidadLink
        FROM CargosEmpleado
        INNER JOIN CargosEmpresa
            ON CargosEmpresa.idCargoEmpleado = CargosEmpleado.idCargoEmpleado
            AND CargosEmpresa.RutEmpresa = @RutEmpresa
    END;
END;
GO
