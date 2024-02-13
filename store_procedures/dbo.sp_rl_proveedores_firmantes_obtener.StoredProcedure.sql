USE [Sodexo_RBK]
GO
/****** Object:  StoredProcedure [dbo].[sp_rl_proveedores_firmantes_obtener]    Script Date: 1/22/2024 7:21:15 PM ******/
SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
-- =============================================
-- Autor: Haydelis Hernandez
-- Creado el: 11/10/2019
-- Descripcion: Obtener firmantes de la Empresa Cliente 
-- Modificado por: Gdiaz 11/01/2021
-- Ejemplo:exec sp_rl_proveedores_firmantes_obtener '22604213-K' 
-- =============================================
CREATE PROCEDURE [dbo].[sp_rl_proveedores_firmantes_obtener]
	@RutProveedor VARCHAR (10),
	@RutEmpresa VARCHAR(10)
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;
	
	DECLARE @lmensaje VARCHAR(100)
	DECLARE @error INT
	
	IF EXISTS (SELECT RutUsuario FROM rl_Firmantes_Proveedores WHERE  RutProveedor=@RutProveedor)
	 BEGIN
		SELECT 
			rl_Firmantes_Proveedores.RutUsuario,
		--	rl_Firmantes_Proveedores.RutEmpresa,
			personas.nacionalidad,
			personas.nombre,
			personas.appaterno,
			personas.apmaterno,
			usuarios.idFirma,
			Firmas.Descripcion,
			personas.correo,
			rl_Firmantes_Proveedores.idCargo,
			Cargos.Descripcion as Cargo,
			ISNULL(personas.nombre,'') +  ' ' + ISNULL(personas.appaterno,'') + ' ' + ISNULL(personas.apmaterno,'') as nombrecompleto,
			rl_Firmantes_Proveedores.RutProveedor,
			ROW_NUMBER()Over(Order by personas.personaid) As correlativo
		FROM rl_Firmantes_Proveedores 
			INNER JOIN personas ON personas.personaid = rl_Firmantes_Proveedores.RutUsuario
			LEFT JOIN usuarios ON usuarios.usuarioid = personas.personaid
			LEFT JOIN Firmas ON Firmas.idFirma = usuarios.idFirma 
			LEFT JOIN Cargos ON Cargos.idCargo = rl_Firmantes_Proveedores.idCargo
		WHERE RutProveedor = @RutProveedor
		--AND  rl_Firmantes_Proveedores.RutEmpresa = @RutEmpresa 

		return
	END		
	ELSE
		BEGIN 
			SELECT @lmensaje = 'El Cliente seleccionada no tiene firmantes asociados'
			SELECT @error = 1
			SELECT @lmensaje as mensaje, @error as error
			RETURN 
		END
END
GO
