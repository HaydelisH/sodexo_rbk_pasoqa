USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_CargosEmpleado_obtener2]    Script Date: 1/22/2024 7:21:13 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE PROCEDURE [dbo].[sp_CargosEmpleado_obtener2]
    @RutEmpresa VARCHAR(10),
    @idCargoEmpleado VARCHAR(14)
AS
BEGIN
	SET NOCOUNT ON;
	BEGIN
        SELECT
            CargosEmpresa.RutEmpresa,
            CargosEmpleado.idCargoEmpleado,
            CargosEmpleado.Titulo,
            CargosEmpleado.Titulo As TituloCargo,
            CargosEmpleado.Obligaciones,
            CargosEmpleado.Obligaciones As ObligacionesCargo,
            CargosEmpleado.Descripcion,
            CargosEmpleado.Descripcion As DescripcionCargo,
            Empresas.RazonSocial
        FROM CargosEmpleado
        INNER JOIN CargosEmpresa
            ON CargosEmpresa.idCargoEmpleado = CargosEmpleado.idCargoEmpleado
            AND CargosEmpresa.RutEmpresa = @RutEmpresa
            AND CargosEmpleado.idCargoEmpleado = @idCargoEmpleado
        INNER JOIN Empresas
            ON Empresas.RutEmpresa = CargosEmpresa.RutEmpresa;
    END;
END;
GO
